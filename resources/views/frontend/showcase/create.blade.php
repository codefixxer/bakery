@extends('frontend.layouts.app')

@section('title', $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase')

@section('content')
    @php
        $maxItems      = 100;
        $oldItems      = old('items') ? (array) old('items') : [];
        $oldCount      = count($oldItems);
        $existingCount = $isEdit ? $showcase->recipes->count() : 0;
        $rowCount      = $oldCount
            ? min($maxItems, $oldCount)
            : ($isEdit ? $existingCount : 1);
    @endphp

    <div class="container py-5">
        <div class="card border-primary shadow-sm mb-4">
            <div class="card-header d-flex align-items-center" style="background-color: #041930;">
                <i class="bi bi-calendar-day fs-2 me-3" style="color: #e2ae76;"></i>
                <h4 class="mb-0 fw-bold" style="color: #e2ae76;">
                    {{ $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase' }}
                </h4>
            </div>
            <div class="card-body">
                <form method="POST"
                      action="{{ $isEdit ? route('showcase.update', $showcase) : route('showcase.store') }}"
                      class="needs-validation" novalidate>
                    @csrf
                    @if($isEdit) @method('PUT') @endif

<<<<<<< HEAD
        <div class="container py-5">
            <div class="card border-primary shadow-sm mb-4">
                <div class="card-header d-flex align-items-center" style="background-color: #041930; padding: .5rem 1rem; border-radius: .75rem;">
  <!-- SVG Showcase Icon -->
  <svg
    class="me-3"
    viewBox="0 0 512.005 512.005"
    xmlns="http://www.w3.org/2000/svg"
    style="width: 1.5em; height: 1.7em; color: #e2ae76; fill: currentColor; font-size:0.8vw;"
  >
    <g>
      <path d="M159.669,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
               C207.835,216.779,186.269,238.344,159.669,238.344z"/>
      <path d="M352.331,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
               C400.496,216.779,378.932,238.344,352.331,238.344z"/>
      <rect x="191.378" y="312.192" width="129.249" height="178.209"/>
    </g>
    <path d="M496.828,104.985c8.379,0,15.172-6.792,15.172-15.172V58.537c0-28.728-23.372-52.099-52.099-52.099
             h-59.404h-96.332h-96.331h-96.332H52.099C23.372,6.437,0,29.809,0,58.537V190.18
             c0,20.106,9.428,38.04,24.084,49.651v250.563c0,8.379,6.792,15.172,15.172,15.172h152.122h129.244h152.124
             c8.379,0,15.172-6.792,15.172-15.172V312.189c0-8.379-6.792-15.172-15.172-15.172
             c-8.379,0-15.172,6.792-15.172,15.172v163.032h-121.78V312.189c0-8.379-6.792-15.172-15.172-15.172
             H191.378c-8.379,0-15.172,6.792-15.172,15.172v163.032H54.428V252.878
             c2.913,0.413,5.885,0.639,8.91,0.639c19.267,0,36.54-8.659,48.166-22.275
             c11.626,13.617,28.899,22.275,48.166,22.275s36.54-8.659,48.166-22.275
             c11.626,13.617,28.899,22.275,48.166,22.275s36.54-8.659,48.166-22.275
             c11.626,13.617,28.899,22.275,48.166,22.275c19.267,0,36.54-8.659,48.166-22.275
             c11.626,13.617,28.899,22.275,48.166,22.275c34.924,0,63.338-28.414,63.338-63.338
             v-26.232c0-8.379-6.792-15.172-15.172-15.172s-15.172,6.792-15.172,15.172v26.232
             c0,18.193-14.8,32.994-32.994,32.994s-32.994-14.8-32.994-32.994V36.78h44.232
             c11.996,0,21.755,9.76,21.755,21.755v31.277C481.656,98.193,488.449,104.985,496.828,104.985z"/>
  </svg>

  <h4 class="mb-0 fw-bold" style="color: #e2ae76; font-size: 1.5rem;">
    {{ $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase' }}
  </h4>
</div>

                <div class="card-body">
                    <form method="POST"
                        action="{{ $isEdit ? route('showcase.update', $showcase) : route('showcase.store') }}"
                        class="needs-validation" novalidate>
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label for="showcase_name" class="form-label fw-semibold" id="showcaseNameLabel">
                                    Showcase Name
                                </label>
                                <input type="text" id="showcase_name" name="showcase_name"
                                    class="form-control form-control-lg"
                                    value="{{ old('showcase_name', $isEdit ? $showcase->showcase_name : '') }}" required>
                                <div class="invalid-feedback">Please enter a showcase name.</div>
                            </div>

                            @unless ($isEdit)
                                <div class="col-md-4">
                                    <label for="template_select" class="form-label fw-semibold">Choose Template</label>
                                    <select id="template_select" name="template_id" class="form-select form-select-lg">
                                        <option value="">-- Select Template --</option>
                                        @foreach ($templates as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endunless

                            <div class="col-md-2">
                                <label for="showcase_date" class="form-label fw-semibold">Select Date</label>
                                <input type="date" id="showcase_date" name="showcase_date"
                                    class="form-control form-control-lg"
                                    value="{{ old('showcase_date', $isEdit ? $showcase->showcase_date->format('Y-m-d') : '') }}"
                                    required>
                                <div class="invalid-feedback">Please select a date.</div>
                            </div>

                            <div class="col-md-2">
                                <label for="template_action" class="form-label fw-semibold">Save As</label>
                                <select id="template_action" name="template_action" class="form-select form-select-lg">
                                    <option value="none" @selected(old('template_action', $isEdit ? $showcase->template_action : 'none') === 'none')>Just Save</option>
                                    <option value="template" @selected(old('template_action', $isEdit ? $showcase->template_action : '') === 'template')>Save as Template</option>
                                    <option value="both" @selected(old('template_action', $isEdit ? $showcase->template_action : '') === 'both')>Save & Template</option>
                                </select>
                            </div>
=======
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <label for="showcase_name" class="form-label fw-semibold" id="showcaseNameLabel">
                                Showcase Name
                            </label>
                            <input type="text" id="showcase_name" name="showcase_name"
                                   class="form-control form-control-lg"
                                   value="{{ old('showcase_name', $isEdit ? $showcase->showcase_name : '') }}"
                                   required>
                            <div class="invalid-feedback">Please enter a showcase name.</div>
>>>>>>> 81d6059cf6241d74d4fdc33ce1a579db3da6efaa
                        </div>

                        @unless($isEdit)
                            <div class="col-md-4">
                                <label for="template_select" class="form-label fw-semibold">
                                    Choose Template
                                </label>
                                <select id="template_select" name="template_id" class="form-select form-select-lg">
                                    <option value="">-- Select Template --</option>
                                    @foreach($templates as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endunless

                        <div class="col-md-2">
                            <label for="showcase_date" class="form-label fw-semibold">Select Date</label>
                            <input type="date" id="showcase_date" name="showcase_date"
                                   class="form-control form-control-lg"
                                   value="{{ old('showcase_date', $isEdit ? $showcase->showcase_date->format('Y-m-d') : '') }}"
                                   required>
                            <div class="invalid-feedback">Please select a date.</div>
                        </div>

                        <div class="col-md-2">
                            <label for="template_action" class="form-label fw-semibold">Save As</label>
                            <select id="template_action" name="template_action" class="form-select form-select-lg">
                                <option value="none"
                                    @selected(old('template_action', $isEdit ? $showcase->template_action : 'none')==='none')>
                                    Just Save
                                </option>
                                <option value="template"
                                    @selected(old('template_action', $isEdit ? $showcase->template_action : '')==='template')>
                                    Save as Template
                                </option>
                                <option value="both"
                                    @selected(old('template_action', $isEdit ? $showcase->template_action : '')==='both')>
                                    Save & Template
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="card border-secondary shadow-sm mb-4">
                        <div class="card-header" style="background-color: #041930;">
                            <strong style="color: #e2ae76;">Showcase Products</strong>
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0" id="showcaseTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Recipe</th>
                                        <th>Price</th>
                                        <th>Ing. Cost</th>
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
                                                        class="form-select form-select-sm recipe-select"
                                                        style="min-width: 250px;" required>
                                                    <option value="">Select Recipe</option>
                                                    @foreach($recipes as $rec)
                                                        <option value="{{ $rec->id }}"
                                                            data-price="{{ $rec->sell_mode==='kg'
                                                                ? $rec->selling_price_per_kg
                                                                : $rec->selling_price_per_piece }}"
                                                            data-batch-ing-cost="{{ $rec->batch_ing_cost }}"
                                                            data-total-pieces="{{ $rec->total_pieces }}"
                                                            data-recipe-weight="{{ $rec->recipe_weight }}"
                                                            data-ingredients-grams="{{ $rec->ingredients->sum(fn($ing)=>$ing->quantity_g) }}"
                                                            data-sell-mode="{{ $rec->sell_mode }}"
                                                            @selected(old("items.$i.recipe_id",
                                                               $item->recipe_id??'')==$rec->id)>
                                                            {{ $rec->recipe_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <input type="text"
                                                           name="items[{{ $i }}][price]"
                                                           class="form-control form-control-sm text-end price-field"
                                                           readonly
                                                           value="{{ old("items.$i.price", $item->price??'') }}">
                                                    <span class="input-group-text unit-field"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">€</span>
                                                    <input type="text"
                                                           class="form-control form-control-sm text-end unit-ing-field"
                                                           readonly value="0.00"
                                                           style="max-width:80px;">
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="items[{{ $i }}][quantity]"
                                                       class="form-control form-control-sm text-center qty-field"
                                                       style="max-width:80px;"
                                                       value="{{ old("items.$i.quantity", $item->quantity??0) }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="items[{{ $i }}][sold]"
                                                       class="form-control form-control-sm text-center sold-field"
                                                       style="max-width:80px;"
                                                       value="{{ old("items.$i.sold", $item->sold??0) }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="items[{{ $i }}][reuse]"
                                                       class="form-control form-control-sm text-center reuse-field"
                                                       style="max-width:80px;"
                                                       value="{{ old("items.$i.reuse", $item->reuse??0) }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="items[{{ $i }}][waste]"
                                                       class="form-control form-control-sm text-center waste-field"
                                                       style="max-width:80px;"
                                                       value="{{ old("items.$i.waste", $item->waste??0) }}"
                                                       required>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">€</span>
                                                    <input type="text"
                                                           name="items[{{ $i }}][potential_income]"
                                                           class="form-control form-control-sm potential-field"
                                                           readonly
                                                           value="{{ old("items.$i.potential_income", $item->potential_income??'') }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">€</span>
                                                    <input type="text"
                                                           name="items[{{ $i }}][actual_revenue]"
                                                           class="form-control form-control-sm revenue-field"
                                                           readonly
                                                           value="{{ old("items.$i.actual_revenue", $item->actual_revenue??'') }}">
                                                </div>
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
                                <button type="button" id="addRowBtn"
                                        class="btn btn-gold-outline btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Add Recipe
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-end justify-content-between flex-wrap mb-4 gap-3">
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Daily Break-even (€)</label>
                            <input type="number" id="break_even" name="break_even"
                                   class="form-control form-control-sm" style="width:140px;"
                                   value="{{ old('break_even',
                                      $isEdit ? $showcase->break_even : $laborCost->daily_bep) }}"
                                   readonly>
                        </div>
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Total Potential (€)</label>
                            <input type="text" id="totalPotential"
                                   class="form-control form-control-sm" style="width:140px;"
                                   readonly name="total_potential">
                        </div>
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Total Revenue (€)</label>
                            <input type="text" id="totalRevenue" name="total_revenue"
                                   class="form-control form-control-sm" style="width:140px;"
                                   readonly>
                        </div>
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Plus (€)</label>
                            <input type="text" id="plusAmount"
                                   class="form-control form-control-sm" style="width:140px;"
                                   readonly name="plus">
                        </div>
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Real Margin (€)</label>
                            <input type="text" id="realMargin" name="real_margin"
                                   class="form-control form-control-sm" style="width:140px;"
                                   readonly>
                        </div>
                        <div class="d-flex align-items-end">
                            <button type="submit" class="btn btn-gold-filled px-5 py-3">
                                <i class="bi bi-save2 me-1"></i>
                                {{ $isEdit ? 'Update Showcase' : 'Save Showcase' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody       = document.querySelector('#showcaseTable tbody');
    const addBtn      = document.getElementById('addRowBtn');
    const breakEvenIn = document.getElementById('break_even');
    let idx           = tbody.querySelectorAll('.showcase-row').length;
    let blankRow;

    setTimeout(() => {
        blankRow = tbody.querySelector('.showcase-row').cloneNode(true);
    }, 50);

    addBtn.addEventListener('click', () => {
        const clone = blankRow.cloneNode(true);
        clone.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${idx}]`);
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else el.value = el.type === 'number' ? '0' : '';
        });
        tbody.appendChild(clone);
        recalcRow(clone);
        idx++;
    });

    function recalcRow(row) {
        const opt      = row.querySelector('.recipe-select').selectedOptions[0];
        const price    = parseFloat(opt.dataset.price) || 0;
        const sellMode = opt.dataset.sellMode;
        const qty      = parseFloat(row.querySelector('.qty-field').value) || 0;
        const sold     = parseFloat(row.querySelector('.sold-field').value) || 0;
        const waste    = parseFloat(row.querySelector('.waste-field').value) || 0;

        // ingredient cost logic from recipe list
        const batchIngCost       = parseFloat(opt.dataset.batchIngCost) || 0;
        const recipeWeight       = parseFloat(opt.dataset.recipeWeight) || 0;
        const ingredientsGrams   = parseFloat(opt.dataset.ingredientsGrams) || 0;
        const wLoss              = recipeWeight || ingredientsGrams;
        const kg                 = (wLoss / 1000) || 1;
        const unitIngCost        = sellMode === 'piece'
                                  ? batchIngCost / (parseFloat(opt.dataset.totalPieces) || 1)
                                  : batchIngCost / kg;

        row.querySelector('.price-field').value     = price.toFixed(2);
        row.querySelector('.unit-field').textContent= sellMode === 'kg' ? '€/kg' : '€/pc';
        row.querySelector('.unit-ing-field').value  = unitIngCost.toFixed(2);
        row.querySelector('.potential-field').value = (price * qty).toFixed(2);
        row.querySelector('.revenue-field').value   = (price * sold).toFixed(2);

        recalcSummary();
    }

    function recalcSummary() {
        let totalRevenue  = 0,
            totalPotential= 0,
            ingSold       = 0,
            ingWaste      = 0;

        tbody.querySelectorAll('.showcase-row').forEach(row => {
            const price   = parseFloat(row.querySelector('.price-field').value) || 0;
            const qty     = parseFloat(row.querySelector('.qty-field').value)   || 0;
            const sold    = parseFloat(row.querySelector('.sold-field').value)  || 0;
            const waste   = parseFloat(row.querySelector('.waste-field').value) || 0;
            const unitIng = parseFloat(row.querySelector('.unit-ing-field').value) || 0;

            totalPotential += price * qty;
            totalRevenue   += price * sold;
            ingSold        += unitIng * sold;
            ingWaste       += unitIng * waste;
        });

        const plus       = totalRevenue - (parseFloat(breakEvenIn.value) || 0);
        const formula1   = totalRevenue > 0
                           ? ((ingSold + ingWaste) / totalRevenue) * 100
                           : 0;
        let realMargin   = plus - ((plus / 100) * formula1);
        if (plus < 0) realMargin = 0;

        document.getElementById('totalPotential').value = totalPotential.toFixed(2);
        document.getElementById('totalRevenue').value   = totalRevenue.toFixed(2);

        const plusEl = document.getElementById('plusAmount');
        plusEl.value = plus.toFixed(2);
        plusEl.style.setProperty('color', plus < 0 ? 'red' : 'green', 'important');

        document.getElementById('realMargin').value = realMargin.toFixed(2);
    }

    tbody.addEventListener('change', e => {
        if (e.target.matches('.recipe-select')) {
            recalcRow(e.target.closest('tr'));
        }
    });
    tbody.addEventListener('input', e => {
        if (e.target.matches('.qty-field, .sold-field, .waste-field')) {
            recalcRow(e.target.closest('tr'));
        }
    });
    tbody.addEventListener('click', e => {
        if (e.target.closest('.remove-row') && tbody.children.length > 1) {
            e.target.closest('tr').remove();
            recalcSummary();
        }
    });

    // initial run
    tbody.querySelectorAll('.showcase-row').forEach(r => recalcRow(r));
});
</script>
@endsection
