{{-- hassam --}}

@extends('frontend.layouts.app')

@section('title', $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase')

@section('content')
<style>
  .department-list {
    max-height: 10rem;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: .25rem;
  }
</style>

<div class="container py-5">
  <h2 class="mb-4">{{ $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase' }}</h2>

  <form method="POST" action="{{ $isEdit ? route('showcase.update', $showcase->id) : route('showcase.store') }}">
    @csrf
    @if($isEdit)
      @method('PUT')
    @endif

    {{-- Department & Date & Save‑As --}}
    <div class="row mb-4 g-3 align-items-end">
      <div class="col-md-3">
        <label for="deptSearch" class="form-label fw-semibold">Department</label>
        <input type="text" id="deptSearch" class="form-control mb-2" placeholder="Search departments…">
        <div class="list-group department-list" id="departmentList">
          @foreach($departments as $dept)
            <label class="list-group-item dept-entry d-flex align-items-center">
              <input
                class="form-check-input me-2"
                type="radio"
                name="department_id"
                value="{{ $dept->id }}"
                @checked(old('department_id', $isEdit ? $showcase->department_id : '') == $dept->id)
                required
              >
              <span class="dept-name">{{ $dept->name }}</span>
            </label>
          @endforeach
        </div>
      </div>

      <div class="col-md-3">
        <label for="showcase_date" class="form-label fw-semibold">Select Date</label>
        <input
          type="date"
          id="showcase_date"
          name="showcase_date"
          class="form-control"
          value="{{ old('showcase_date', $isEdit ? $showcase->showcase_date : '') }}"
          required
        >
      </div>

      <div class="col-md-3">
        <label for="template_action" class="form-label fw-semibold">Save As</label>
        <select id="template_action" name="template_action" class="form-select">
          <option value="none" @selected(old('template_action', $isEdit ? $showcase->template_action : '') === 'none')>Just Save</option>
          <option value="template" @selected(old('template_action', $isEdit ? $showcase->template_action : '') === 'template')>Save as Template</option>
          <option value="both" @selected(old('template_action', $isEdit ? $showcase->template_action : '') === 'both')>Save and Template</option>
        </select>
      </div>
    </div>

    {{-- Showcase Products Table --}}
    <div class="card shadow-sm border-primary mb-4">
      <div class="card-header bg-primary text-white">
        <strong>Showcase Products</strong>
      </div>
      <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0" id="showcaseTable">
          <thead class="table-light">
            <tr>
              <th>Recipe</th>
              <th class="col-price">Price ($)</th>
              <th class="text-center col-small">Qty Displayed</th>
              <th class="text-center col-small">Total Sold</th>
              <th class="text-center col-small">Reuse</th>
              <th class="text-center col-small">Waste</th>
              <th>Potential Income ($)</th>
              <th>Actual Revenue ($)</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @php
              $maxItems      = 100;
              $oldItems      = old('items') ? (array)old('items') : [];
              $oldCount      = count($oldItems);
              $existingCount = $isEdit ? $showcase->recipes->count() : 0;
              $count = $oldCount ? min($maxItems,$oldCount) : ($isEdit ? $existingCount : 1);
            @endphp

            @for ($i = 0; $i < $count; $i++)
              @php
                $item = $oldItems[$i] ?? ($isEdit && isset($showcase->recipes[$i]) ? $showcase->recipes[$i] : null);
              @endphp
              <tr class="showcase-row">
                <td>
                  <select name="items[{{ $i }}][recipe_id]" class="form-select recipe-select" required>
                    <option value="">Select Recipe</option>
                    @foreach($recipes as $rec)
                      <option
                        value="{{ $rec->id }}"
                        data-price="{{ $rec->sell_mode==='kg' ? $rec->selling_price_per_kg : $rec->selling_price_per_piece }}"
                        data-sell-mode="{{ $rec->sell_mode }}"
                        @selected(old("items.$i.recipe_id", $item->recipe_id ?? null)==$rec->id)
                      >{{ $rec->recipe_name }}</option>
                    @endforeach
                  </select>
                </td>

                <td>
                  <div class="input-group">
                    <span class="input-group-text">€</span>
                    <input
                      type="text"
                      name="items[{{ $i }}][price]"
                      class="form-control price-field"
                      readonly
                      value="{{ old("items.$i.price", $item->price ?? '') }}"
                    >
                    <span class="input-group-text unit-field"></span>
                  </div>
                </td>

                <td>
                  <input
                    type="number"
                    name="items[{{ $i }}][quantity]"
                    class="form-control qty-field"
                    required
                    value="{{ old("items.$i.quantity", $item->quantity ?? 0) }}"
                  >
                </td>

                <td>
                  <input
                    type="number"
                    name="items[{{ $i }}][sold]"
                    class="form-control sold-field"
                    required
                    value="{{ old("items.$i.sold", $item->sold ?? 0) }}"
                  >
                </td>

                <td>
                  <input
                    type="number"
                    name="items[{{ $i }}][reuse]"
                    class="form-control reuse-field"
                    step="1"
                    required
                    value="{{ old("items.$i.reuse", $item->reuse ?? 0) }}"
                  >
                </td>

                <td>
                  <input
                    type="number"
                    name="items[{{ $i }}][waste]"
                    class="form-control waste-field"
                    step="1"
                    required
                    value="{{ old("items.$i.waste", $item->waste ?? 0) }}"
                  >
                </td>

                <td>
                  <input
                    type="text"
                    name="items[{{ $i }}][potential_income]"
                    class="form-control potential-field"
                    readonly
                    value="{{ old("items.$i.potential_income", $item->potential_income ?? '') }}"
                  >
                </td>

                <td>
                  <input
                    type="text"
                    name="items[{{ $i }}][actual_revenue]"
                    class="form-control revenue-field"
                    readonly
                    value="{{ old("items.$i.actual_revenue", $item->actual_revenue ?? '') }}"
                  >
                </td>

                <td class="text-center">
                  <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            @endfor
          </tbody>
        </table>

        <div class="p-3 border-top text-end">
          <button type="button" id="addRowBtn" class="btn btn-outline-success btn-sm">
            <i class="bi bi-plus-circle"></i> Add Recipe
          </button>
        </div>
      </div>
    </div>


       <div class="row mb-4 g-3">
        <div class="col-md-3">
          <label for="break_even" class="form-label fw-semibold">Daily Break-even ($)</label>
          <input
            type="number"
            name="break_even"
            id="break_even"
            class="form-control"
            value="{{ old('break_even', $isEdit ? $showcase->break_even : ($defaultBreakEven ?? 0)) }}"
          >
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Total Revenue ($)</label>
          <input
            type="text"
            id="totalRevenue"
            name="total_revenue"
            class="form-control"
            value="{{ old('total_revenue', $isEdit ? $showcase->total_revenue : '') }}"
            readonly
          >
        </div>
   <div class="col-md-3">
     <label class="form-label fw-semibold">Total Potential ($)</label>
     <input
       type="text"
       id="totalPotential"
       name="total_potential"
       class="form-control"
       value="{{ old('total_potential', $isEdit ? $showcase->total_potential : '') }}"
       readonly
     >
   </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Plus ($)</label>
          <input
            type="text"
            id="plusValue"
            name="plus"
            class="form-control"
            value="{{ old('plus', $isEdit ? $showcase->plus : '') }}"
            readonly
          >
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Real Margin (%)</label>
          <input
            type="text"
            id="realMargin"
            name="real_margin"
            class="form-control"
            value="{{ old('real_margin', $isEdit ? $showcase->real_margin : '') }}"
            readonly
          >
        </div>
      </div>
  

    <div class="text-end">
      <button type="submit" class="btn btn-primary btn-lg">
        <i class="bi bi-save"></i> {{ $isEdit ? 'Update' : 'Save' }} Showcase
      </button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
{{-- Department filter --}}
<script>
window.addEventListener('load', function() {
  const searchInput = document.getElementById('deptSearch'),
        items       = document.querySelectorAll('.dept-entry');

  searchInput?.addEventListener('input', function() {
    const filter = this.value.toLowerCase().trim();
    items.forEach(item => {
      const name = item.querySelector('.dept-name')?.textContent.toLowerCase() || '';
      item.style.display = name.includes(filter) ? 'flex' : 'none';
    });
  });
});
</script>

{{-- Real-time table logic & calculations --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  let rowIndex = document.querySelectorAll('.showcase-row').length;
  const tbody  = document.querySelector('#showcaseTable tbody'),
        addBtn = document.getElementById('addRowBtn');

  // 1) Add new row
  addBtn.addEventListener('click', () => {
    const firstRow = tbody.querySelector('.showcase-row'),
          newRow   = firstRow.cloneNode(true);
    newRow.querySelectorAll('input, select').forEach(el => {
      el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
      if (el.tagName === 'SELECT') el.selectedIndex = 0;
      else el.value = (el.type === 'number') ? '0' : '';
    });
    tbody.appendChild(newRow);
    rowIndex++;
  });

  // 2) Recalculate a single row
  function recalcRow(row) {
    const opt      = row.querySelector('.recipe-select')?.selectedOptions[0],
          priceIn  = row.querySelector('.price-field'),
          unitSpan = row.querySelector('.unit-field'),
          qtyIn    = row.querySelector('.qty-field'),
          soldIn   = row.querySelector('.sold-field'),
          potIn    = row.querySelector('.potential-field'),
          revIn    = row.querySelector('.revenue-field');

    if (opt) {
      const price = parseFloat(opt.dataset.price || 0).toFixed(2),
            mode  = opt.dataset.sellMode || 'piece';
      priceIn.value       = price;
      unitSpan.textContent = mode === 'kg' ? '/kg' : '/piece';
    }

    const priceVal = parseFloat(priceIn.value) || 0,
          qtyVal   = parseFloat(qtyIn.value)  || 0,
          soldVal  = parseFloat(soldIn.value) || 0;

    potIn.value = (priceVal * qtyVal).toFixed(2);
    revIn.value = (priceVal * soldVal).toFixed(2);
    calcSummary();
  }

  // 3) Overall summary (Total Revenue, Total Potential, Plus, Real Margin)
  function calcSummary() {
    let totalRev       = 0,
        totalPotential = 0;

    document.querySelectorAll('.revenue-field').forEach(el => {
      totalRev += parseFloat(el.value) || 0;
    });
    document.querySelectorAll('.potential-field').forEach(el => {
      totalPotential += parseFloat(el.value) || 0;
    });

    const breakEven = parseFloat(document.getElementById('break_even').value) || 0,
          plus      = totalRev - breakEven,
          pct       = totalRev ? (plus / totalRev) * 100 : 0;

    document.getElementById('totalRevenue').value   = totalRev.toFixed(2);
    document.getElementById('totalPotential').value = totalPotential.toFixed(2);
    document.getElementById('plusValue').value      = plus.toFixed(2);
    document.getElementById('realMargin').value     = pct.toFixed(2) + '%';
  }

  // 4) Wire up events
  tbody.addEventListener('input', e => {
    if (e.target.matches('.qty-field, .sold-field, .reuse-field, .waste-field')) {
      recalcRow(e.target.closest('tr'));
    }
  });
  tbody.addEventListener('change', e => {
    if (e.target.matches('.recipe-select')) {
      recalcRow(e.target.closest('tr'));
    }
  });
  tbody.addEventListener('click', e => {
    if (e.target.closest('.remove-row') &&
        tbody.querySelectorAll('.showcase-row').length > 1) {
      e.target.closest('tr').remove();
      calcSummary();
    }
  });

  // 5) Initial calculation pass
  document.querySelectorAll('.showcase-row').forEach(r => recalcRow(r));
  document.getElementById('break_even').addEventListener('input', calcSummary);
});
</script>
@endsection

