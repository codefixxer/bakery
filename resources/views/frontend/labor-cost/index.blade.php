{{-- resources/views/labor_cost_card.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Labor & BEP Calculator')

@section('content')
@php $lc = optional($laborCost); @endphp

<div class="container py-5">
  <form method="POST" action="{{ route('labor-cost.store') }}">
    @csrf

    <div class="card shadow-sm">
      <div class="card-header d-flex align-items-center" style="background-color: #041930; color: #e2ae76;">
        <i class="bi bi-calculator fs-5 me-2" style="color: #e2ae76;"></i>
        <h5 class="mb-0 fw-bold"style=" color: #e2ae76;"">Labor &amp; BEP Calculator</h5>
      </div>
      <div class="card-body">

        @if($laborCost && $laborCost->user)
  <div class="mb-3">
    <strong>Updated By:</strong>
    <span class="badge bg-light text-dark">
      {{ $laborCost->user->name }}
    </span>
  </div>
@endif
        {{-- 1) Top inputs --}}
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label">Number of Chefs</label>
            <input type="number" id="numChefs" name="num_chefs" class="form-control" min="1"
                   value="{{ old('num_chefs', $lc->num_chefs ?? 1) }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Opening Days (this month)</label>
            <input type="number" id="openDays" name="opening_days" class="form-control" min="1"
                   value="{{ old('opening_days', $lc->opening_days ?? 22) }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Opening Hours / Day</label>
            <input type="number" id="hoursPerDay" name="hours_per_day" class="form-control" min="0"
                   value="{{ old('hours_per_day', $lc->hours_per_day ?? 8) }}">
          </div>
        </div>

        <hr>

        {{-- 2) Cost categories --}}
        <div class="row g-3 mb-3">
          @foreach ([
              'Electricity'     => 'electricity',
              'Ingredients'     => 'ingredients',
              'Leasing/Loan'    => 'leasing_loan',
              'Packaging'       => 'packaging',
              'Owner'           => 'owner',
              'Van Rental'      => 'van_rental',
              'Chefs'           => 'chefs',
              'Shop Assistants' => 'shop_assistants',
              'Other Salaries'  => 'other_salaries',
              'Taxes'           => 'taxes',
              'Other Categories'=> 'other_categories',
              'Driver Salary'   => 'driver_salary',
          ] as $label => $field)
          <div class="col-md-4">
            <label class="form-label">{{ $label }}</label>
            <input type="number" step="0.01"
                   id="{{ $field }}"
                   name="{{ $field }}"
                   class="form-control cost-input"
                   data-cat="{{ $field }}"
                   value="{{ old($field, $lc->$field ?? 0) }}">
          </div>
          @endforeach
        </div>

        <hr>

        {{-- 3) BEP outputs --}}
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <label class="form-label">Monthly BEP (Total Costs)</label>
            <input type="text" id="monthlyBEP" name="monthly_bep" class="form-control bg-light" readonly>
          </div>
          <div class="col-md-4">
            <label class="form-label">Daily BEP</label>
            <input type="text" id="dailyBEP" name="daily_bep" class="form-control bg-light" readonly>
          </div>
        </div>

        {{-- 4) Scaled labor costs --}}
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Labor Cost / Minute (Shop) × 4/3</label>
            <input type="text"
                   id="shopCostPerMin"
                   name="shop_cost_per_min"
                   class="form-control bg-light"
                   readonly>
            <div class="form-text">
              <code>(
                (Total − Ingredients − Van Rental − Driver Salary)
                ÷ (Days × Hours × 60)
                ÷ Chefs
              ) ÷ 3 × 4</code>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Labor Cost / Minute (External) × 4/3</label>
            <input type="text"
                   id="externalCostPerMin"
                   name="external_cost_per_min"
                   class="form-control bg-light"
                   readonly>
            <div class="form-text">
              <code>(
                (Total − Ingredients − Shop Assistants)
                ÷ (Days × Hours × 60)
                ÷ Chefs
              ) ÷ 3 × 4</code>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-gold-blue btn-lg">
          <i class="bi bi-save2 me-1"></i> Save Labor &amp; BEP Details
        </button>
        
        

      </div>
    </div>
  </form>
</div>
@endsection
<style>
  .btn-gold-blue {
  background-color: #e2ae76 !important;
  color: #041930 !important;
  border: 1px solid #e2ae76 !important;
}
.btn-gold-blue i {
  color: #041930 !important;
}
.btn-gold-blue:hover {
  background-color: #d89d5c !important;
  color: white !important;
}


</style>
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
  const byId = id => document.getElementById(id);

  const numChefs    = byId('numChefs'),
        openDays    = byId('openDays'),
        hoursPerDay = byId('hoursPerDay');

  const costs = Array.from(document.querySelectorAll('.cost-input'));

  const monthlyEl  = byId('monthlyBEP'),
        dailyEl    = byId('dailyBEP'),
        shopEl     = byId('shopCostPerMin'),
        externalEl = byId('externalCostPerMin');

  function recalc(){
    const total = costs.reduce((sum, el) => sum + (parseFloat(el.value)||0), 0);
    monthlyEl.value = total.toFixed(2);

    const days = Math.max(1, parseInt(openDays.value)||1);
    dailyEl.value = (total / days).toFixed(2);

    const mins  = days * (parseFloat(hoursPerDay.value)||0) * 60,
          chefs = Math.max(1, parseInt(numChefs.value)||1);

    const getCost = key => {
      const el = document.querySelector(`.cost-input[data-cat="${key}"]`);
      return el ? (parseFloat(el.value)||0) : 0;
    };
    const ing = getCost('ingredients'),
          van = getCost('van_rental'),
          drv = getCost('driver_salary'),
          sa  = getCost('shop_assistants');

    const shopOfficial = mins > 0
      ? (total - ing - van - drv) / mins / chefs
      : 0;
    const externalOfficial = mins > 0
      ? (total - ing - sa) / mins / chefs
      : 0;

    shopEl.value     = (shopOfficial / 3 * 4).toFixed(4);
    externalEl.value = (externalOfficial / 3 * 4).toFixed(4);
  }

  [ numChefs, openDays, hoursPerDay, ...costs ].forEach(el =>
    el.addEventListener('input', recalc)
  );
  document.querySelector('form').addEventListener('submit', recalc);

  recalc();
});
</script>
@endsection
