{{-- @extends('frontend.layouts.app')

@section('title', 'Labor Cost per Minute')

@section('content')
<div class="container py-5">


  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0 text-white"><i class="bi bi-clock-history me-2"></i>Labor Cost per Minute</h5>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('labor-cost.store') }}">
        @csrf

        <div class="mb-3">
          <label for="cost_per_minute" class="form-label fw-semibold">
            Cost per Minute ($)
          </label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
            <input
              type="number"
              step="0.01"
              id="cost_per_minute"
              name="cost_per_minute"
              class="form-control @error('cost_per_minute') is-invalid @enderror"
              value="{{ old('cost_per_minute', optional($laborCost)->cost_per_minute) }}"
              required
            >
            @error('cost_per_minute')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="bi bi-save2 me-1"></i> Save
        </button>
      </form>
    </div>
  </div>
</div>
@endsection --}}



{{-- resources/views/labor_cost_card.blade.php --}}
@extends('frontend.layouts.app')

@section('content')
    <form method="POST" action="{{ route('labor-cost.store') }}">
        @csrf

        <div class="container py-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Labor &amp; BEP Calculator</h5>
                </div>
                <div class="card-body">

                    {{-- 1) Top inputs --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Number of Chefs</label>
                            <input type="number" id="numChefs" name="num_chefs" class="form-control" min="1"
                                value="{{ old('num_chefs', 1) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Opening Days (this month)</label>
                            <input type="number" id="openDays" name="opening_days" class="form-control" min="1"
                                value="{{ old('opening_days', 22) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Opening Hours / Day</label>
                            <input type="number" id="hoursPerDay" name="hours_per_day" class="form-control" min="0"
                                value="{{ old('hours_per_day', 8) }}">
                        </div>
                    </div>

                    <hr>

                    {{-- 2) Cost categories --}}
                    <div class="row g-3 mb-3">
                        @foreach (['Electricity', 'Ingredients', 'Leasing/Loan', 'Packaging', 'Owner', 'Van Rental', 'Chefs', 'Shop Assistants', 'Other Salaries', 'Taxes', 'Other Categories', 'Driver Salary'] as $cat)
                            @php
                                $field = strtolower(str_replace([' ', '/'], '_', $cat));
                            @endphp
                            <div class="col-md-4">
                                <label class="form-label">{{ $cat }}</label>
                                <input type="number" step="0.01" id="{{ $field }}" name="{{ $field }}"
                                    class="form-control cost-input" data-cat="{{ str_replace('_', '', $field) }}"
                                    value="{{ old($field, 0) }}">
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    {{-- 3) Calculated fields --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Monthly BEP (Total Costs)</label>
                            <input type="text" id="monthlyBEP" name="monthly_bep" class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Daily BEP</label>
                            <input type="text" id="dailyBEP" name="daily_bep" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Labor Cost / Minute (Shop)</label>
                            <input type="text" id="shopCostPerMin" name="shop_cost_per_min" class="form-control"
                                readonly>
                            <div class="form-text">
                                (Total − Ingredients − Van Rental − Driver Salary) ÷ (Days × Hours × 60) ÷ Chefs
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Labor Cost / Minute (External)</label>
                            <input type="text" id="externalCostPerMin" name="external_cost_per_min" class="form-control"
                                readonly>
                            <div class="form-text">
                                (Total − Ingredients − Shop Assistants) ÷ (Days × Hours × 60) ÷ Chefs
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Labor &amp; BEP Details
                    </button>

                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
      const byId = id => document.getElementById(id);
    
      // Top inputs
      const numChefs    = byId('numChefs');
      const openDays    = byId('openDays');
      const hoursPerDay = byId('hoursPerDay');
    
      // All cost-category inputs (must have class="cost-input" and data-cat="yourkey")
      const costs = Array.from(document.querySelectorAll('.cost-input'));
    
      // Output fields
      const monthlyEl  = byId('monthlyBEP');
      const dailyEl    = byId('dailyBEP');
      const shopEl     = byId('shopCostPerMin');
      const externalEl = byId('externalCostPerMin');
    
      function recalc() {
        // 1) Sum total of all cost categories
        const total = costs.reduce((sum, el) => sum + (parseFloat(el.value) || 0), 0);
        monthlyEl.value = total.toFixed(2);
    
        // 2) Daily BEP = total / days
        const days = Math.max(1, parseInt(openDays.value) || 1);
        dailyEl.value = (total / days).toFixed(2);
    
        // 3) Denominator for per-minute calcs
        const minutes = days * (parseFloat(hoursPerDay.value) || 0) * 60;
        const chefs   = Math.max(1, parseInt(numChefs.value) || 1);
    
        // Helper to grab a cost by its data-cat key
        const getCost = key => {
          const el = document.querySelector(`.cost-input[data-cat="${key}"]`);
          return el ? (parseFloat(el.value) || 0) : 0;
        };
    
        const ing       = getCost('ingredients');
        const van       = getCost('vanrental');
        const driver    = getCost('driversalary');
        const shopAsst  = getCost('shopassistants');
    
        // 4) Shop labor cost per min per chef
        shopEl.value = minutes > 0
          ? ((total - ing - van - driver) / minutes / chefs).toFixed(4)
          : '0.0000';
    
        // 5) External labor cost per min per chef
        externalEl.value = minutes > 0
          ? ((total - ing - shopAsst) / minutes / chefs).toFixed(4)
          : '0.0000';
      }
    
      // Recalculate whenever any relevant input changes
      [numChefs, openDays, hoursPerDay, ...costs].forEach(el =>
        el.addEventListener('input', recalc)
      );
    
      // Also recalc right before submit to ensure fresh values
      const form = document.querySelector('form');
      form.addEventListener('submit', recalc);
    
      // Initial calculation on page load
      recalc();
    });
    </script>
    
@endsection