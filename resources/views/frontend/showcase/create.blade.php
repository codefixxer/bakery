{{-- resources/views/frontend/showcase/create.blade.php --}}

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
                      class="needs-validation" novalidate id="showcaseForm">
                    @csrf
                    @if($isEdit) @method('PUT') @endif

                    <div class="row g-4 mb-4">
                        {{-- Showcase Name --}}
                        <div class="col-md-4">
                            <label for="showcase_name" class="form-label fw-semibold" id="showcaseNameLabel">
                                Showcase Name
                            </label>
                            <input type="text"
                                   id="showcase_name"
                                   name="showcase_name"
                                   class="form-control form-control-lg"
                                   value="{{ old('showcase_name', $isEdit ? $showcase->showcase_name : '') }}"
                                   required>
                            <div class="invalid-feedback">Please enter a showcase name.</div>
                        </div>

                        {{-- Choose Template (only on create) --}}
                        @unless($isEdit)
                        <div class="col-md-4">
                            <label for="template_select" class="form-label fw-semibold">
                                Choose Template
                            </label>
                            <select id="template_select"
                                    name="template_id"
                                    class="form-select form-select-lg"
                                    onchange="
                                      const opt = this.selectedOptions[0];
                                      if (opt && opt.value) {
                                        document.getElementById('showcase_name').value = opt.dataset.name;
                                        document.getElementById('showcase_date').value = opt.dataset.date;
                                        document.getElementById('break_even').value   = opt.dataset.breakEven;
                                      }
                                      this.dispatchEvent(new Event('change',{bubbles:true}));
                                    ">
                                <option value="">-- Select Template --</option>
                                @foreach($templates as $template)
                                    <option
                                      value="{{ $template->id }}"
                                      data-name="{{ $template->showcase_name }}"
                                      data-date="{{ $template->showcase_date->format('Y-m-d') }}"
                                      data-break-even="{{ $template->break_even }}">
                                      {{ $template->showcase_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endunless

                        {{-- Select Date --}}
                        <div class="col-md-2">
                            <label for="showcase_date" class="form-label fw-semibold">Select Date</label>
                            <input type="date"
                                   id="showcase_date"
                                   name="showcase_date"
                                   class="form-control form-control-lg"
                                   value="{{ old('showcase_date', $isEdit ? $showcase->showcase_date->format('Y-m-d') : '') }}"
                                   required>
                            <div class="invalid-feedback">Please select a date.</div>
                        </div>

                        {{-- Save As --}}
                        <div class="col-md-2">
                            <label for="template_action" class="form-label fw-semibold">Save As</label>
                            <select id="template_action"
                                    name="template_action"
                                    class="form-select form-select-lg">
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

                    {{-- Products Table --}}
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
                                        {{-- Ing. Cost column removed --}}
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
                                                        <option
                                                          value="{{ $rec->id }}"
                                                          data-price="{{ $rec->sell_mode==='kg'
                                                              ? $rec->selling_price_per_kg
                                                              : $rec->selling_price_per_piece }}"
                                                          data-batch-ing-cost="{{ $rec->batch_ing_cost }}"
                                                          data-total-pieces="{{ $rec->total_pieces }}"
                                                          data-recipe-weight="{{ $rec->recipe_weight }}"
                                                          data-ingredients-grams="{{ $rec->ingredients->sum(fn($ing)=>$ing->quantity_g) }}"
                                                          data-sell-mode="{{ $rec->sell_mode }}"
                                                          @selected(old("items.$i.recipe_id", $item->recipe_id ?? '')==$rec->id)>
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
                                                           style="font-family:monospace;"
                                                           readonly
                                                           value="{{ old("items.$i.price", $item->price ?? '') }}">
                                                    <span class="input-group-text unit-field"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="items[{{ $i }}][quantity]"
                                                       class="form-control form-control-sm text-center qty-field"
                                                       style="max-width:90px; font-family:monospace;"
                                                       value="{{ old("items.$i.quantity", $item->quantity ?? 0) }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="items[{{ $i }}][sold]"
                                                       class="form-control form-control-sm text-center sold-field"
                                                       style="max-width:90px; font-family:monospace;"
                                                       value="{{ old("items.$i.sold", $item->sold ?? 0) }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="items[{{ $i }}][reuse]"
                                                       class="form-control form-control-sm text-center reuse-field"
                                                       style="max-width:90px; font-family:monospace;"
                                                       value="{{ old("items.$i.reuse", $item->reuse ?? 0) }}"
                                                       required>
                                            </td>
                                            <td>
                                                <input type="number"
                                                       name="items[{{ $i }}][waste]"
                                                       class="form-control form-control-sm text-center waste-field"
                                                       style="max-width:90px; font-family:monospace;"
                                                       value="{{ old("items.$i.waste", $item->waste ?? 0) }}"
                                                       required>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">€</span>
                                                    <input type="text"
                                                           name="items[{{ $i }}][potential_income]"
                                                           class="form-control form-control-sm potential-field"
                                                           style="font-family:monospace;"
                                                           readonly
                                                           value="{{ old("items.$i.potential_income", $item->potential_income ?? '') }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">€</span>
                                                    <input type="text"
                                                           name="items[{{ $i }}][actual_revenue]"
                                                           class="form-control form-control-sm revenue-field"
                                                           style="font-family:monospace;"
                                                           readonly
                                                           value="{{ old("items.$i.actual_revenue", $item->actual_revenue ?? '') }}">
                                                </div>
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
  <button type="button" id="addRowBtn"
          class="btn btn-sm"
          style="border: 1px solid #e2ae76; color: #041930; background-color: transparent;"
          onmouseover="this.style.backgroundColor='#e2ae76'; this.style.color='white'; this.querySelector('i').style.color='white';"
          onmouseout="this.style.backgroundColor='transparent'; this.style.color='#041930'; this.querySelector('i').style.color='#041930';">
    <i class="bi bi-plus-circle me-1" style="color: #041930;"></i>
    Add Recipe
  </button>
</div>

                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="d-flex align-items-end justify-content-between flex-wrap mb-4 gap-3">
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Daily Break-even (€)</label>
                            <input type="number"
                                   id="break_even"
                                   name="break_even"
                                   class="form-control form-control-sm"
                                   style="width:140px;"
                                   value="{{ old('break_even', $isEdit ? $showcase->break_even : $laborCost->daily_bep) }}"
                                   readonly>
                        </div>
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Total Potential (€)</label>
                            <input type="text"
                                   id="totalPotential"
                                   name="total_potential"
                                   class="form-control form-control-sm"
                                   style="width:140px; font-family:monospace;"
                                   readonly>
                        </div>
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Total Revenue (€)</label>
                            <input type="text"
                                   id="totalRevenue"
                                   name="total_revenue"
                                   class="form-control form-control-sm"
                                   style="width:140px; font-family:monospace;"
                                   readonly>
                        </div>
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Plus (€)</label>
                            <input type="text"
                                   id="plusAmount"
                                   name="plus"
                                   class="form-control form-control-sm"
                                   style="width:140px; font-family:monospace;"
                                   readonly>
                        </div>
                        <div class="d-flex flex-column">
                            <label class="form-label fw-semibold">Real Margin (€)</label>
                            <input type="text"
                                   id="realMargin"
                                   name="real_margin"
                                   class="form-control form-control-sm"
                                   style="width:140px; font-family:monospace;"
                                   readonly>
                        </div>
                        <div class="d-flex align-items-end">
                            <button type="submit" class="btn btn-gold-filled px-5 py-3" style="background-color: #e2ae76; color: #041930;">
                                <i class="bi bi-save2 me-1"  style="color: #041930;"></i>
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
    const tbody          = document.querySelector('#showcaseTable tbody');
    const addBtn         = document.getElementById('addRowBtn');
    const breakEvenIn    = document.getElementById('break_even');
    const templateSelect = document.getElementById('template_select');
    const actionSelect   = document.getElementById('template_action');
    const nameLabel      = document.getElementById('showcaseNameLabel');
    const nameInput      = document.getElementById('showcase_name');
    const dateInput      = document.getElementById('showcase_date');
    let idx              = tbody.querySelectorAll('.showcase-row').length;
    let blankRow;

    function updateNameRequirement() {
        const v = actionSelect.value;
        nameLabel.textContent = (v === 'template' || v === 'both')
            ? 'Template Name'
            : 'Showcase Name';
        nameInput.required = (v === 'template' || v === 'both');
    }
    actionSelect.addEventListener('change', updateNameRequirement);
    updateNameRequirement();

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
        const opt      = row.querySelector('.recipe-select').selectedOptions[0] || {};
        const price    = parseFloat(opt.dataset.price)        || 0;
        const sellMode = opt.dataset.sellMode                 || 'piece';
        const qty      = parseFloat(row.querySelector('.qty-field').value)  || 0;
        const sold     = parseFloat(row.querySelector('.sold-field').value) || 0;
        row.querySelector('.price-field').value      = price.toFixed(2);
        row.querySelector('.unit-field').textContent = sellMode==='kg'?'€/kg':'€/pc';
        row.querySelector('.potential-field').value  = (price*qty).toFixed(2);
        row.querySelector('.revenue-field').value    = (price*sold).toFixed(2);
        recalcSummary();
    }

    function recalcSummary() {
        let totalPot = 0, totalRev = 0;
        tbody.querySelectorAll('.showcase-row').forEach(row => {
            const price = parseFloat(row.querySelector('.price-field').value) || 0;
            const qty   = parseFloat(row.querySelector('.qty-field').value)   || 0;
            const sold  = parseFloat(row.querySelector('.sold-field').value)  || 0;
            totalPot += price * qty;
            totalRev += price * sold;
        });
        const plus = totalRev - (parseFloat(breakEvenIn.value) || 0);
        document.getElementById('totalPotential').value = totalPot.toFixed(2);
        document.getElementById('totalRevenue').value   = totalRev.toFixed(2);
        document.getElementById('plusAmount').value     = plus.toFixed(2);
        document.getElementById('realMargin').value    = Math.max(0, plus).toFixed(2);
    }

    tbody.addEventListener('change', e => {
        if (e.target.matches('.recipe-select')) recalcRow(e.target.closest('tr'));
    });
    tbody.addEventListener('input', e => {
        if (e.target.matches('.qty-field, .sold-field, .waste-field')) recalcRow(e.target.closest('tr'));
    });
    tbody.addEventListener('click', e => {
        if (e.target.closest('.remove-row') && tbody.children.length>1) {
            e.target.closest('tr').remove();
            recalcSummary();
        }
    });

    templateSelect?.addEventListener('change', function() {
        const id = this.value;
        if (!id) return;
        fetch(`/showcase/template/${id}`)
            .then(r => r.json())
            .then(data => {
                nameInput.value   = data.showcase_name;
                dateInput.value   = data.showcase_date;
                breakEvenIn.value = data.break_even;
                tbody.innerHTML   = '';
                data.details.forEach((d,i) => {
                    const r = blankRow.cloneNode(true);
                    r.querySelector('.recipe-select').value = d.recipe_id;
                    r.querySelector('.qty-field').value    = d.quantity;
                    r.querySelector('.sold-field').value   = d.sold;
                    r.querySelector('.waste-field').value  = d.waste;
                    r.querySelectorAll('input, select').forEach(el => {
                        el.name = el.name.replace(/\[\d+\]/, `[${i}]`);
                    });
                    tbody.appendChild(r);
                    recalcRow(r);
                });
                idx = data.details.length;
            })
            .catch(console.error);
    });

    tbody.querySelectorAll('.showcase-row').forEach(r => recalcRow(r));
});
</script>
@endsection
