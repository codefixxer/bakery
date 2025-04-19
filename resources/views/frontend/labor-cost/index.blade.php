{{-- @extends('frontend.layouts.app')

@section('title','Labor Cost per Minute')

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
          <input type="number" id="numChefs" class="form-control" min="1" value="1">
        </div>
        <div class="col-md-4">
          <label class="form-label">Opening Days (this month)</label>
          <input type="number" id="openDays" class="form-control" min="1" value="22">
        </div>
        <div class="col-md-4">
          <label class="form-label">Opening Hours / Day</label>
          <input type="number" id="hoursPerDay" class="form-control" min="0" value="8">
        </div>
      </div>

      <hr>

      {{-- 2) Cost categories --}}
      <div class="row g-3">
        @foreach([
          'Electricity','Ingredients','Leasing/Loan','Packaging','Owner','Van Rental',
          'Chefs','Shop Assistants','Other Salaries','Taxes','Other Categories','Driver Salary'
        ] as $cat)
          <div class="col-md-4">
            <label class="form-label">{{ $cat }}</label>
            <input type="number" step="0.01" class="form-control cost-input" 
                   data-cat="{{ strtolower(str_replace([' ','/'],'',$cat)) }}" value="0">
          </div>
        @endforeach
      </div>

      <hr>

      {{-- 3) Calculated fields --}}
      <div class="row g-3 mb-3">
        <div class="col-md-4">
          <label class="form-label">Monthly BEP (Total Costs)</label>
          <input type="text" id="monthlyBEP" class="form-control" readonly>
        </div>
        <div class="col-md-4">
          <label class="form-label">Daily BEP</label>
          <input type="text" id="dailyBEP" class="form-control" readonly>
        </div>
      </div>

      <div class="row g-3 mb-3">
        <div class="col-md-6">
          <label class="form-label">Labor Cost / Minute (Shop)</label>
          <input type="text" id="shopCostPerMin" class="form-control" readonly>
          <div class="form-text">
            (Total − Ingredients − Van Rental − Driver Salary) ÷ (Days × Hours × 60) ÷ Chefs
          </div>
        </div>
        <div class="col-md-6">
          <label class="form-label">Labor Cost / Minute (External)</label>
          <input type="text" id="externalCostPerMin" class="form-control" readonly>
          <div class="form-text">
            (Total − Ingredients − Shop Assistants) ÷ (Days × Hours × 60) ÷ Chefs
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const byId       = id => document.getElementById(id),
        numChefs   = byId('numChefs'),
        openDays   = byId('openDays'),
        hoursPerDay= byId('hoursPerDay'),
        costs      = Array.from(document.querySelectorAll('.cost-input')),
        // also select the Showcase break_even input by its ID:
        showcaseBEP= byId('break_even');

  function recalc() {
    // 1) Monthly BEP
    const total = costs.reduce((sum, el) => sum + parseFloat(el.value||0), 0);
    byId('monthlyBEP').value = total.toFixed(2);

    // 2) Daily BEP
    const days = Math.max(1, parseInt(openDays.value)||1),
          daily= total/days;
    byId('dailyBEP').value = daily.toFixed(2);

    // ** push that same daily into the Showcase BEP field **
    if (showcaseBEP) showcaseBEP.value = daily.toFixed(2);

    // 3) compute minutes
    const hrs      = parseFloat(hoursPerDay.value)||0,
          minutes  = days*hrs*60,
          chefs    = Math.max(1, parseInt(numChefs.value)||1);

    // cost exclusions by data-cat:
    const cat = key => parseFloat(document.querySelector(`[data-cat="${key}"]`).value||0);

    const ing      = cat('ingredients'),
          van      = cat('vanrental'),
          driver   = cat('driversalary'),
          shopAsst = cat('shopassistants');

    // Shop labor cost / min / chef
    const shopSum = total - ing - van - driver;
    byId('shopCostPerMin').value = minutes>0
      ? (shopSum/minutes/chefs).toFixed(4)
      : '0.0000';

    // External labor cost / min / chef
    const extSum = total - ing - shopAsst;
    byId('externalCostPerMin').value = minutes>0
      ? (extSum/minutes/chefs).toFixed(4)
      : '0.0000';
  }

  // watch all relevant inputs
  [ numChefs, openDays, hoursPerDay, ...costs ]
    .forEach(el => el.addEventListener('input', recalc));

  // initial calculation
  recalc();
});
</script>
@endsection

