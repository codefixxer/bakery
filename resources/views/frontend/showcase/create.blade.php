{{-- resources/views/frontend/showcase/form.blade.php --}}
@extends('frontend.layouts.app')

@section('title', $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase')

@section('content')
@php
    $maxItems      = 100;
    $oldItems      = old('items') ? (array)old('items') : [];
    $oldCount      = count($oldItems);
    $existingCount = $isEdit ? $showcase->recipes->count() : 0;
    $rowCount      = $oldCount
                      ? min($maxItems, $oldCount)
                      : ($isEdit ? $existingCount : 1);
@endphp

<div class="container-fluid py-5">
  <h2 class="mb-4">{{ $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase' }}</h2>

  <form method="POST"
        action="{{ $isEdit ? route('showcase.update',$showcase->id) : route('showcase.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- Showcase Name --}}
    <div class="mb-4">
      <label for="showcase_name" class="form-label fw-semibold" id="showcaseNameLabel">
        Showcase Name
      </label>
      <input type="text"
             id="showcase_name"
             name="showcase_name"
             class="form-control"
             value="{{ old('showcase_name', $isEdit ? $showcase->showcase_name : '') }}"
             required>
    </div>

    @if(! $isEdit)
      {{-- Choose Template --}}
      <div class="mb-4">
        <label for="template_select" class="form-label fw-semibold">Choose Template</label>
        <select id="template_select"
                name="template_id"
                class="form-select">
          <option value="">-- Select Template --</option>
          @foreach($templates as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
          @endforeach
        </select>
      </div>
    @endif

    {{-- Date & Template Action --}}
    <div class="row mb-4 g-3 align-items-end">
      <div class="col-md-4">
        <label for="showcase_date" class="form-label fw-semibold">Select Date</label>
        <input type="date"
               id="showcase_date"
               name="showcase_date"
               class="form-control"
               value="{{ old('showcase_date', $isEdit ? $showcase->showcase_date->format('Y-m-d') : '') }}"
               required>
      </div>
      <div class="col-md-4">
        <label for="template_action" class="form-label fw-semibold">Save As</label>
        <select id="template_action"
                name="template_action"
                class="form-select">
          <option value="none"     @selected(old('template_action', $isEdit ? $showcase->template_action : 'none')==='none')>Just Save</option>
          <option value="template" @selected(old('template_action', $isEdit ? $showcase->template_action : '')==='template')>Save as Template</option>
          <option value="both"     @selected(old('template_action', $isEdit ? $showcase->template_action : '')==='both')>Save &amp; Template</option>
        </select>
      </div>
    </div>

    {{-- Products Table --}}
    <div class="card shadow-sm border-primary mb-4">
      <div class="card-header bg-primary text-white"><strong>Showcase Products</strong></div>
      <div class="card-body p-0">
        <table class="table mb-0" id="showcaseTable">
          <thead class="table-light">
            <tr>
              <th>Recipe</th>
              <th>Department</th>
              <th>Price</th>
              <th class="text-center">Qty</th>
              <th class="text-center">Sold</th>
              <th class="text-center">Reuse</th>
              <th class="text-center">Waste</th>
              <th>Potential</th>
              <th>Actual</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            @for($i=0; $i<$rowCount; $i++)
              @php
                $item = $oldItems[$i]
                      ?? ($isEdit && isset($showcase->recipes[$i])
                          ? $showcase->recipes[$i]
                          : null);
              @endphp
              <tr class="showcase-row">
                <td>
                  <select name="items[{{ $i }}][recipe_id]"
                          class="form-select recipe-select"
                          required>
                    <option value="">Select Recipe</option>
                    @foreach($recipes as $rec)
                      <option
                        value="{{ $rec->id }}"
                        data-price="{{ $rec->sell_mode==='kg'
                                        ? $rec->selling_price_per_kg
                                        : $rec->selling_price_per_piece }}"
                        data-ingredients-cost="{{ $rec->ingredients_total_cost }}"
                        data-recipe-weight="{{ $rec->recipe_weight }}"
                        data-sell-mode="{{ $rec->sell_mode }}"
                        data-dept-name="{{ optional($rec->department)->name }}"
                        @selected(old("items.$i.recipe_id", $item->recipe_id ?? '') == $rec->id)>
                        {{ $rec->recipe_name }}
                      </option>
                    @endforeach
                  </select>
                </td>
                <td class="dept-field align-middle"></td>
                <td>
                  <div class="input-group input-group-sm">
                    <span class="input-group-text">€</span>
                    <input type="text"
                           name="items[{{ $i }}][price]"
                           class="form-control text-end price-field"
                           readonly
                           value="{{ old("items.$i.price", $item->price ?? '') }}">
                    <span class="input-group-text unit-field"></span>
                  </div>
                </td>
                <td>
                  <input type="number"
                         name="items[{{ $i }}][quantity]"
                         class="form-control qty-field text-center"
                         value="{{ old("items.$i.quantity", $item->quantity ?? 0) }}"
                         required>
                </td>
                <td>
                  <input type="number"
                         name="items[{{ $i }}][sold]"
                         class="form-control sold-field text-center"
                         value="{{ old("items.$i.sold", $item->sold ?? 0) }}"
                         required>
                </td>
                <td>
                  <input type="number"
                         name="items[{{ $i }}][reuse]"
                         class="form-control reuse-field text-center"
                         value="{{ old("items.$i.reuse", $item->reuse ?? 0) }}"
                         required>
                </td>
                <td>
                  <input type="number"
                         name="items[{{ $i }}][waste]"
                         class="form-control waste-field text-center"
                         value="{{ old("items.$i.waste", $item->waste ?? 0) }}"
                         required>
                </td>
                <td>
                  <input type="text"
                         name="items[{{ $i }}][potential_income]"
                         class="form-control potential-field" readonly
                         value="{{ old("items.$i.potential_income", $item->potential_income ?? '') }}">
                </td>
                <td>
                  <input type="text"
                         name="items[{{ $i }}][actual_revenue]"
                         class="form-control revenue-field" readonly
                         value="{{ old("items.$i.actual_revenue", $item->actual_revenue ?? '') }}">
                </td>
                <td class="text-center">
                  <button type="button"
                          class="btn btn-outline-danger btn-sm remove-row">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            @endfor
          </tbody>
        </table>
        <div class="p-3 border-top text-end">
          <button type="button"
                  id="addRowBtn"
                  class="btn btn-outline-success btn-sm">
            <i class="bi bi-plus-circle"></i> Add Recipe
          </button>
        </div>
      </div>
    </div>

    {{-- Summary --}}
    <div class="row mb-4 g-3">
      <div class="col-md-2">
        <label class="form-label fw-semibold">Daily Break-even (€)</label>
        <input type="number"
               id="break_even"
               class="form-control"
               value="{{ old('break_even',$isEdit?$showcase->break_even:($laborCost->daily_bep??0)) }}"
               disabled>
        <div class="form-text text-muted">Preset daily cost to cover fixed expenses</div>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">Total Revenue (€)</label>
        <input type="text"
               id="totalRevenue"
               class="form-control"
               readonly>
        <div class="form-text text-muted">∑ of all “Actual” values</div>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">Plus (€)</label>
        <input type="text"
               id="plusAmount"
               class="form-control"
               readonly>
        <div class="form-text text-muted">Plus = Total Revenue − Break-even</div>
        <div class="form-text text-muted fm-plus-pct"></div>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">Total Potential (€)</label>
        <input type="text"
               id="totalPotential"
               class="form-control"
               readonly>
        <div class="form-text text-muted">∑ of all “Potential” values</div>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold">Real Margin (€)</label>
        <input type="text"
               id="realMargin"
               class="form-control"
               readonly>
        <div class="form-text text-muted">Real Margin = Plus − (Incidence% of Plus)</div>
        <div class="form-text text-muted fm-cost-pct"></div>
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
<script>
document.addEventListener('DOMContentLoaded', () => {
  const actionSelect  = document.getElementById('template_action');
  const nameLabel     = document.getElementById('showcaseNameLabel');
  // 1) initial label on page load
  (function() {
    const v = actionSelect.value;
    nameLabel.textContent =
      (v === 'template' || v === 'both')
        ? 'Template Name'
        : 'Showcase Name';
  })();
  // 2) live toggle
  actionSelect.addEventListener('change', () => {
    const v = actionSelect.value;
    nameLabel.textContent =
      (v === 'template' || v === 'both')
        ? 'Template Name'
        : 'Showcase Name';
  });

  let idx             = document.querySelectorAll('.showcase-row').length;
  const tbody         = document.querySelector('#showcaseTable tbody');
  const addBtn        = document.getElementById('addRowBtn');
  const bepIn         = document.getElementById('break_even');
  const templateSel   = document.getElementById('template_select');
  const nameInput     = document.getElementById('showcase_name');
  const dateInput     = document.getElementById('showcase_date');

  // grab blank row
  let blankRow;
  setTimeout(() => {
    blankRow = tbody.querySelector('.showcase-row').cloneNode(true);
  }, 50);

  // add row
  addBtn.addEventListener('click', () => {
    const clone = blankRow.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(el => {
      el.name = el.name.replace(/\[\d+\]/, `[${idx}]`);
      if (el.tagName==='SELECT') el.selectedIndex = 0;
      else el.value = (el.type==='number') ? '0' : '';
    });
    tbody.appendChild(clone);
    recalcRow(clone);
    idx++;
  });

  // recalc row
  function recalcRow(row) {
    const opt   = row.querySelector('.recipe-select').selectedOptions[0];
    const price = parseFloat(opt.dataset.price||0).toFixed(2);
    row.querySelector('.price-field').value   = price;
    row.querySelector('.unit-field').textContent = opt.dataset.sellMode==='kg' ? '/kg':'/pc';
    row.querySelector('.dept-field').textContent = opt.dataset.deptName||'';
    const qty  = +row.querySelector('.qty-field').value   || 0;
    const sold = +row.querySelector('.sold-field').value  || 0;
    row.querySelector('.potential-field').value = (price*qty).toFixed(2);
    row.querySelector('.revenue-field').value   = (price*sold).toFixed(2);
    recalcSummary();
  }

  // recalc summary
  function recalcSummary() {
    let totRev=0, totPot=0, rawCost=0;
    document.querySelectorAll('.revenue-field').forEach(e=> totRev+= +e.value||0);
    document.querySelectorAll('.potential-field').forEach(e=> totPot+= +e.value||0);
    document.querySelectorAll('.showcase-row').forEach(r=>{
      const opt   = r.querySelector('.recipe-select').selectedOptions[0];
      const ing   = +opt.dataset.ingredientsCost||0;
      const wt    = +opt.dataset.recipeWeight||1;
      const sold  = +r.querySelector('.sold-field').value||0;
      const waste = +r.querySelector('.waste-field').value||0;
      const costKg= wt>0 ? ing/wt : 0;
      const out   = opt.dataset.sellMode==='kg' ? (sold+waste) : (sold+waste)*wt;
      rawCost += costKg*out;
    });
    const bep        = +bepIn.value||0;
    const plus       = totRev - bep;
    const incPct     = totRev>0 ? rawCost/totRev*100 : 0;
    const realMargin = plus - (plus*incPct/100);
    const plusPct    = totRev>0 ? plus/totRev*100 : 0;
    const marPct     = plus>0 ? realMargin/plus*100 : 0;
    document.getElementById('totalRevenue').value   = totRev.toFixed(2);
    document.getElementById('plusAmount').value     = plus.toFixed(2);
    document.getElementById('totalPotential').value = totPot.toFixed(2);
    document.getElementById('realMargin').value     = realMargin.toFixed(2);
    document.querySelector('.fm-plus-pct').textContent = `Plus is ${plusPct.toFixed(1)}% of revenue`;
    document.querySelector('.fm-cost-pct').textContent = `Incidence: ${incPct.toFixed(1)}% • Margin on Plus: ${marPct.toFixed(1)}%`;
  }

  // delegate events
  tbody.addEventListener('change', e=>{
    if(e.target.matches('.recipe-select')) recalcRow(e.target.closest('tr'));
  });
  tbody.addEventListener('input', e=>{
    if(e.target.matches('.qty-field, .sold-field, .reuse-field, .waste-field'))
      recalcRow(e.target.closest('tr'));
  });
  tbody.addEventListener('click', e=>{
    if(e.target.closest('.remove-row') && tbody.children.length>1){
      e.target.closest('tr').remove();
      recalcSummary();
    }
  });

  // initial
  tbody.querySelectorAll('.showcase-row').forEach(r=> recalcRow(r));

  // load template
  templateSel?.addEventListener('change', function(){
    const id = this.value;
    if(!id){
      dateInput.value    = '';
      actionSelect.value = 'none';
      actionSelect.dispatchEvent(new Event('change'));
      tbody.innerHTML='';
      const r = blankRow.cloneNode(true);
      r.querySelectorAll('input,select').forEach(el=>{
        el.name = el.name.replace(/\[\d+\]/, `[0]`);
        if(el.tagName==='SELECT') el.selectedIndex=0;
        else el.value = el.type==='number'?'0':'';
      });
      tbody.appendChild(r);
      recalcRow(r);
      idx=1;
      return;
    }
    fetch(`/showcase/template/${id}`)
      .then(res=>res.json())
      .then(data=>{
        dateInput.value    = data.showcase_date;
        actionSelect.value = data.template_action;
        actionSelect.dispatchEvent(new Event('change'));
        tbody.innerHTML='';
        idx=0;
        data.rows.forEach(rowData=>{
          const r = blankRow.cloneNode(true);
          r.querySelectorAll('input,select').forEach(el=>{
            el.name = el.name.replace(/\[\d+\]/, `[${idx}]`);
          });
          r.querySelector('.recipe-select').value   = rowData.recipe_id;
          r.querySelector('.qty-field').value       = rowData.quantity;
          r.querySelector('.sold-field').value      = rowData.sold;
          r.querySelector('.reuse-field').value     = rowData.reuse;
          r.querySelector('.waste-field').value     = rowData.waste;
          r.querySelector('.potential-field').value = parseFloat(rowData.potential_income).toFixed(2);
          r.querySelector('.revenue-field').value   = parseFloat(rowData.actual_revenue).toFixed(2);
          tbody.appendChild(r);
          recalcRow(r);
          idx++;
        });
      })
      .catch(console.error);
  });
});
</script>
@endsection
