    @extends('frontend.layouts.app')

    @section('title', $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase')


    @section('content')
        @php
            $maxItems = 100;
            $oldItems = old('items') ? (array) old('items') : [];
            $oldCount = count($oldItems);
            $existingCount = $isEdit ? $showcase->recipes->count() : 0;
            $rowCount = $oldCount ? min($maxItems, $oldCount) : ($isEdit ? $existingCount : 1);
        @endphp

        <div class="container py-5">
            <div class="card border-primary shadow-sm mb-4">
                <div class="card-header d-flex align-items-center" style="background-color: #041930;">
                    <i class="bi bi-calendar-day fs-2 me-3" style="color: #e2ae76;"></i>
                    <h4 class="mb-0 fw-bold" style="color: #e2ae76;">
                        {{ $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase' }}</h4>
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
                                        @for ($i = 0; $i < $rowCount; $i++)
                                            @php
                                                $item =
                                                    $oldItems[$i] ??
                                                    ($isEdit && isset($showcase->recipes[$i])
                                                        ? $showcase->recipes[$i]
                                                        : null);
                                            @endphp
                                            <tr class="showcase-row">
                                                <td>
                                                    <select name="items[{{ $i }}][recipe_id]"
                                                        class="form-select form-select-sm recipe-select"
                                                        style="min-width: 250px;" required>
                                                        <option value="">Select Recipe</option>
                                                        @foreach ($recipes as $rec)
                                                            <option value="{{ $rec->id }}"
                                                                data-price="{{ $rec->sell_mode === 'kg' ? $rec->selling_price_per_kg : $rec->selling_price_per_piece }}"
                                                                data-ingredients-cost="{{ $rec->ingredients_total_cost }}"
                                                                data-recipe-weight="{{ $rec->recipe_weight }}"
                                                                data-sell-mode="{{ $rec->sell_mode }}"
                                                                @selected(old("items.$i.recipe_id", $item->recipe_id ?? '') == $rec->id)>
                                                                {{ $rec->recipe_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">€</span>
                                                        <input type="text" name="items[{{ $i }}][price]"
                                                            class="form-control form-control-sm text-end price-field"
                                                            readonly
                                                            value="{{ old("items.$i.price", $item->price ?? '') }}">
                                                        <span class="input-group-text unit-field"></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $i }}][quantity]"
                                                        class="form-control form-control-sm text-center qty-field"
                                                        style="max-width: 80px;"
                                                        value="{{ old("items.$i.quantity", $item->quantity ?? 0) }}"
                                                        required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $i }}][sold]"
                                                        class="form-control form-control-sm text-center sold-field"
                                                        style="max-width: 80px;"
                                                        value="{{ old("items.$i.sold", $item->sold ?? 0) }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $i }}][reuse]"
                                                        class="form-control form-control-sm text-center reuse-field"
                                                        style="max-width: 80px;"
                                                        value="{{ old("items.$i.reuse", $item->reuse ?? 0) }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="items[{{ $i }}][waste]"
                                                        class="form-control form-control-sm text-center waste-field"
                                                        style="max-width: 80px;"
                                                        value="{{ old("items.$i.waste", $item->waste ?? 0) }}" required>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">€</span>
                                                        <input type="text"
                                                            name="items[{{ $i }}][potential_income]"
                                                            class="form-control form-control-sm potential-field" readonly
                                                            value="{{ old("items.$i.potential_income", $item->potential_income ?? '') }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">€</span>
                                                        <input type="text"
                                                            name="items[{{ $i }}][actual_revenue]"
                                                            class="form-control form-control-sm revenue-field" readonly
                                                            value="{{ old("items.$i.actual_revenue", $item->actual_revenue ?? '') }}">
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
                                    <button type="button" id="addRowBtn" class="btn btn-gold-outline btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i>Add Recipe
                                    </button>
                                </div>
                            </div>

                        </div>
                        <div class="d-flex align-items-end justify-content-between flex-wrap mb-4 gap-3">
                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold">Daily Break-even (€)</label>
                                <input type="number" id="break_even" class="form-control form-control-sm"
                                    style="width: 140px;"
                                    value="{{ old('break_even', $isEdit ? $showcase->break_even : $laborCost->daily_bep ?? 0) }}"
                                    disabled>
                            </div>

                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold">Total Revenue (€)</label>
                                <input type="text" id="totalRevenue" class="form-control form-control-sm"
                                    style="width: 140px;" readonly>
                            </div>

                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold">Plus (€)</label>
                                <input type="text" id="plusAmount" class="form-control form-control-sm"
                                    style="width: 140px;" readonly>
                                {{-- <div class="form-text fm-plus-pct"></div> --}}
                            </div>

                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold">Total Potential (€)</label>
                                <input type="text" id="totalPotential" class="form-control form-control-sm"
                                    style="width: 140px;" readonly>
                            </div>

                            <div class="d-flex flex-column">
                                <label class="form-label fw-semibold">Real Margin (€)</label>
                                <input type="text" id="realMargin" class="form-control form-control-sm"
                                    style="width: 140px;" readonly>
                                {{-- <div class="form-text fm-cost-pct"></div> --}}
                            </div>

                            <div class="d-flex align-items-end">
                                <button type="submit" class="btn btn-gold-filled px-5 py-3">
                                    <i class="bi bi-save2 me-1"></i> {{ $isEdit ? 'Update Showcase' : 'Save Showcase' }}
                                </button>
                            </div>

                        </div>


                    </form>
                </div>
            </div>
        </div>
    @endsection


    <style>
        .btn-gold-outline {
            border: 1px solid #e2ae76 !important;
            color: #e2ae76 !important;
            background-color: transparent !important;
            font-weight: 500;
        }

        .btn-gold-outline:hover {
            background-color: #e2ae76 !important;
            color: white !important;
        }

        .btn-gold-filled {
            background-color: #e2ae76 !important;
            color: #041930 !important;
            border: none !important;
            font-weight: 600;
            border-radius: 12px;
            transition: background-color 0.2s ease;
        }

        .btn-gold-filled:hover {
            background-color: #d89d5c !important;
            color: white !important;
        }
    </style>



    @section('content')
        @php
            $maxItems = 100;
            $oldItems = old('items') ? (array) old('items') : [];
            $oldCount = count($oldItems);
            $existingCount = $isEdit ? $showcase->recipes->count() : 0;
            $rowCount = $oldCount ? min($maxItems, $oldCount) : ($isEdit ? $existingCount : 1);
        @endphp

        <div class="container py-5">
            <div class="card border-primary shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-calendar-day fs-2 me-3"></i>
                    <h4 class="mb-0">{{ $isEdit ? 'Edit Daily Showcase' : 'Create Daily Showcase' }}</h4>
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

                            <div class="col-md-4">
                                <label for="showcase_date" class="form-label fw-semibold">Select Date</label>
                                <input type="date" id="showcase_date" name="showcase_date"
                                    class="form-control form-control-lg"
                                    value="{{ old('showcase_date', $isEdit ? $showcase->showcase_date->format('Y-m-d') : '') }}"
                                    required>
                                <div class="invalid-feedback">Please select a date.</div>
                            </div>

                            <div class="col-md-4">
                                <label for="template_action" class="form-label fw-semibold">Save As</label>
                                <select id="template_action" name="template_action" class="form-select form-select-lg">
                                    <option value="none" @selected(old('template_action', $isEdit ? $showcase->template_action : 'none') === 'none')>Just Save</option>
                                    <option value="template" @selected(old('template_action', $isEdit ? $showcase->template_action : '') === 'template')>Save as Template</option>
                                    <option value="both" @selected(old('template_action', $isEdit ? $showcase->template_action : '') === 'both')>Save &amp; Template</option>
                                </select>
                            </div>
                        </div>

                        <div class="card border-secondary shadow-sm mb-4">
                            <div class="card-header bg-secondary text-white"><strong>Showcase Products</strong></div>
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
                                        @for ($i = 0; $i < $rowCount; $i++)
                                            @php
                                                $item =
                                                    $oldItems[$i] ??
                                                    ($isEdit && isset($showcase->recipes[$i])
                                                        ? $showcase->recipes[$i]
                                                        : null);
                                            @endphp
                                            <tr class="showcase-row">
                                                <td>
                                                    <select name="items[{{ $i }}][recipe_id]"
                                                        class="form-select form-select-sm recipe-select" required>
                                                        <option value="">Select Recipe</option>
                                                        @foreach ($recipes as $rec)
                                                            <option value="{{ $rec->id }}"
                                                                data-price="{{ $rec->sell_mode === 'kg' ? $rec->selling_price_per_kg : $rec->selling_price_per_piece }}"
                                                                data-ingredients-cost="{{ $rec->ingredients_total_cost }}"
                                                                data-recipe-weight="{{ $rec->recipe_weight }}"
                                                                data-sell-mode="{{ $rec->sell_mode }}"
                                                                data-dept-name="{{ optional($rec->department)->name }}"
                                                                data-dept-id="{{ optional($rec->department)->id }}"
                                                                @selected(old("items.$i.recipe_id", $item->recipe_id ?? '') == $rec->id)>{{ $rec->recipe_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden"
                                                        name="items[{{ $i }}][department_id]"
                                                        class="dept-id-field"
                                                        value="{{ old("items.$i.department_id", optional($item->recipe->department ?? null)->id) }}">
                                                </td>
                                                <td class="dept-field align-middle"></td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">€</span>
                                                        <input type="text" name="items[{{ $i }}][price]"
                                                            class="form-control form-control-sm text-end price-field"
                                                            readonly
                                                            value="{{ old("items.$i.price", $item->price ?? '') }}">
                                                        <span class="input-group-text unit-field"></span>
                                                    </div>
                                                </td>
                                                <td><input type="number" name="items[{{ $i }}][quantity]"
                                                        class="form-control form-control-sm text-center qty-field"
                                                        value="{{ old("items.$i.quantity", $item->quantity ?? 0) }}"
                                                        required>
                                                </td>
                                                <td><input type="number" name="items[{{ $i }}][sold]"
                                                        class="form-control form-control-sm text-center sold-field"
                                                        value="{{ old("items.$i.sold", $item->sold ?? 0) }}" required>
                                                </td>
                                                <td><input type="number" name="items[{{ $i }}][reuse]"
                                                        class="form-control form-control-sm text-center reuse-field"
                                                        value="{{ old("items.$i.reuse", $item->reuse ?? 0) }}" required>
                                                </td>
                                                <td><input type="number" name="items[{{ $i }}][waste]"
                                                        class="form-control form-control-sm text-center waste-field"
                                                        value="{{ old("items.$i.waste", $item->waste ?? 0) }}" required>
                                                </td>
                                                <td><input type="text"
                                                        name="items[{{ $i }}][potential_income]"
                                                        class="form-control form-control-sm potential-field" readonly
                                                        value="{{ old("items.$i.potential_income", $item->potential_income ?? '') }}">
                                                </td>
                                                <td><input type="text"
                                                        name="items[{{ $i }}][actual_revenue]"
                                                        class="form-control form-control-sm revenue-field" readonly
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
                                    <button type="button" id="addRowBtn" class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i>Add Recipe
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Daily Break-even (€)</label>
                                <input type="number" id="break_even" class="form-control"
                                    value="{{ old('break_even', $isEdit ? $showcase->break_even : $laborCost->daily_bep ?? 0) }}"
                                    disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Total Revenue (€)</label>
                                <input type="text" id="totalRevenue" class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Plus (€)</label>
                                <input type="text" id="plusAmount" class="form-control" readonly>
                                <div class="form-text fm-plus-pct"></div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Total Potential (€)</label>
                                <input type="text" id="totalPotential" class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Real Margin (€)</label>
                                <input type="text" id="realMargin" class="form-control" readonly>
                                <div class="form-text fm-cost-pct"></div>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save2 me-1"></i> {{ $isEdit ? 'Update' : 'Save' }} Showcase
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
                const actionSelect = document.getElementById('template_action');
                const nameLabel = document.getElementById('showcaseNameLabel');
                const nameInput = document.getElementById('showcase_name');

                function updateNameRequirement() {
                    const val = actionSelect.value;
                    const isRequired = val === 'template' || val === 'both';
                    nameLabel.textContent = isRequired ? 'Template Name' : 'Showcase Name';
                    if (isRequired) nameInput.setAttribute('required', 'required');
                    else nameInput.removeAttribute('required');
                }
                updateNameRequirement();
                actionSelect.addEventListener('change', updateNameRequirement);

                // ------- rest of your script (unchanged) --------

                const tbody = document.querySelector('#showcaseTable tbody');
                const addBtn = document.getElementById('addRowBtn');
                const bepIn = document.getElementById('break_even');
                const templateSel = document.getElementById('template_select');
                const dateInput = document.getElementById('showcase_date');

                let idx = tbody.querySelectorAll('.showcase-row').length;
                let blankRow;
                setTimeout(() => {
                    blankRow = tbody.querySelector('.showcase-row').cloneNode(true);
                }, 50);

                addBtn.addEventListener('click', function() {
                    const clone = blankRow.cloneNode(true);
                    clone.querySelectorAll('input, select').forEach(el => {
                        el.name = el.name.replace(/\[\d+\]/, `[${idx}]`);
                        if (el.tagName === 'SELECT') el.selectedIndex = 0;
                        else el.value = (el.type === 'number') ? '0' : '';
                    });
                    tbody.appendChild(clone);
                    recalcRow(clone);
                    idx++;
                });

                function recalcRow(row) {
  const opt       = row.querySelector('.recipe-select').selectedOptions[0];
  const price     = parseFloat(opt.dataset.price  || 0);
  const sellMode  = opt.dataset.sellMode;
  const qty       = parseFloat(row.querySelector('.qty-field').value ) || 0;
  const sold      = parseFloat(row.querySelector('.sold-field').value) || 0;

  // Update price and unit
  row.querySelector('.price-field').value    = price.toFixed(2);
  row.querySelector('.unit-field').textContent = sellMode === 'kg' ? '€/kg' : '€/pc';

  // Calculate potential income and actual revenue
  row.querySelector('.potential-field').value = (price * qty).toFixed(2);
  row.querySelector('.revenue-field').value  = (price * sold).toFixed(2);

  // Recompute the summary row
  recalcSummary();
}


                // —— NEW recalcSummary() per your Real Margin requirements ——
                function recalcSummary() {
                    let totalRevenue = 0;
                    let totalPotential = 0;
                    let rawCost = 0;

                    document.querySelectorAll('.showcase-row').forEach(row => {
                        totalRevenue += parseFloat(row.querySelector('.revenue-field').value) || 0;
                        totalPotential += parseFloat(row.querySelector('.potential-field').value) || 0;

                        const opt = row.querySelector('.recipe-select').selectedOptions[0];
                        const batchCost = parseFloat(opt.dataset.ingredientsCost) || 0;
                        const batchSize = parseFloat(opt.dataset.recipeWeight) || 1;
                        const sold = parseFloat(row.querySelector('.sold-field').value) || 0;
                        const waste = parseFloat(row.querySelector('.waste-field').value) || 0;

                        const unitsMoved = sold + waste;
                        const unitCost = batchSize > 0 ? batchCost / batchSize : 0;
                        rawCost += unitCost * unitsMoved;
                    });

                    const breakEven = parseFloat(document.getElementById('break_even').value) || 0;
                    // never let Plus go negative
                    const plus = Math.max(0, totalRevenue - breakEven);

                    const incidencePct = totalRevenue > 0 ?
                        (rawCost / totalRevenue) * 100 :
                        0;

                    // if Plus is zero, Real Margin must be zero
                    const realMargin = plus > 0 ?
                        plus - (plus * incidencePct / 100) :
                        0;

                    const marginPct = plus > 0 ?
                        (realMargin / plus) * 100 :
                        0;

                    // write back
                    document.getElementById('totalRevenue').value = totalRevenue.toFixed(2);
                    document.getElementById('plusAmount').value = plus.toFixed(2);
                    document.getElementById('totalPotential').value = totalPotential.toFixed(2);
                    document.getElementById('realMargin').value = realMargin.toFixed(2);

                    document.querySelector('.fm-plus-pct').textContent =
                        `Plus is ${(plus / (totalRevenue||1) * 100).toFixed(1)}% of revenue`;
                    document.querySelector('.fm-cost-pct').textContent =
                        `Incidence: ${incidencePct.toFixed(1)}% • Margin on Plus: ${marginPct.toFixed(0)}%`;
                }

                // —— end of new recalcSummary() ——

                tbody.addEventListener('change', function(e) {
                    if (e.target.matches('.recipe-select')) recalcRow(e.target.closest('tr'));
                });
                tbody.addEventListener('input', function(e) {
                    if (e.target.matches('.qty-field, .sold-field, .reuse-field, .waste-field'))
                        recalcRow(e.target.closest('tr'));
                });
                tbody.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-row') && tbody.children.length > 1) {
                        e.target.closest('tr').remove();
                        recalcSummary();
                    }
                });

                // initial bootstrap
                tbody.querySelectorAll('.showcase-row').forEach(r => recalcRow(r));

                templateSel?.addEventListener('change', function() {
                    // your existing template-fetch logic...
                });
            });
        </script>
    @endsection
