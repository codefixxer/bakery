{{-- resources/views/frontend/recipe/create.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Create Recipe')

@section('content')
    <div class="container py-5">
        @php
            $isEdit = isset($recipe);
            $formAction = $isEdit ? route('recipes.update', $recipe->id) : route('recipes.store');
        @endphp

        <form method="POST" action="{{ $formAction }}">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            {{-- 1) Recipe Details --}}
            <div class="card mb-4 border-primary shadow-sm">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="bi bi-journal-text fs-4 me-2"></i>
                    <h5 class="mb-0">Recipe Details</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                        {{-- Recipe name --}}
                        <div class="col-md-4">
                            <label for="recipeName" class="form-label fw-semibold">Name</label>
                            <input type="text" id="recipeName" name="recipe_name" class="form-control"
                                placeholder="Chocolate Cake"
                                value="{{ old('recipe_name', $isEdit ? $recipe->recipe_name : '') }}" required>
                        </div>

                        {{-- Category dropdown --}}
                        <div class="col-md-4">
                            <label for="recipeCategory" class="form-label fw-semibold">Category</label>
                            <select id="recipeCategory" name="recipe_category_id" class="form-select" required>
                                <option value="">Choose…</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('recipe_category_id', $isEdit ? $recipe->recipe_category_id ?? '' : '') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Department dropdown --}}
                        <div class="col-md-4">
                            <label for="recipeDept" class="form-label fw-semibold">Department</label>
                            <select id="recipeDept" name="department_id" class="form-select" required>
                                <option value="">Choose…</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ old('department_id', $isEdit ? $recipe->department_id ?? '' : '') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 2) Ingredients --}}
            <div class="card mb-4 border-info shadow-sm">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <i class="bi bi-list-ul fs-4 me-2"></i>
                    <h5 class="mb-0">Ingredients</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ingredient</th>
                                    <th class="text-center">Qty&nbsp;(g)</th>
                                    <th class="text-center">Cost&nbsp;($)</th>
                                    <th class="text-center">Incidence</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="ingredientsTable">
                                @if ($isEdit && isset($recipe->ingredients) && $recipe->ingredients->isNotEmpty())
                                    @foreach ($recipe->ingredients as $index => $line)
                                        <tr class="ingredient-row">
                                            <td>
                                                <select name="ingredients[{{ $index }}][id]"
                                                    class="form-select ingredient-select" required>
                                                    <option value="">Select ingredient…</option>
                                                    @foreach ($ingredients as $ing)
                                                        <option value="{{ $ing->id }}"
                                                            data-price="{{ $ing->price_per_kg }}"
                                                            {{ $ing->id == $line->ingredient_id ? 'selected' : '' }}>
                                                            {{ $ing->ingredient_name }} (${{ $ing->price_per_kg }}/kg)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01"
                                                    name="ingredients[{{ $index }}][quantity]"
                                                    class="form-control text-center ingredient-quantity"
                                                    value="{{ old("ingredients.$index.quantity", $line->quantity_g) }}"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="text" name="ingredients[{{ $index }}][cost]"
                                                    class="form-control text-center ingredient-cost" readonly
                                                    value="{{ old("ingredients.$index.cost", $line->cost) }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control text-center ingredient-incidence"
                                                    readonly>
                                            </td>
                                            <td class="text-center">
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm remove-ingredient">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="ingredient-row">
                                        <td>
                                            <select name="ingredients[0][id]" class="form-select ingredient-select"
                                                required>
                                                <option value="">Select ingredient…</option>
                                                @foreach ($ingredients as $ing)
                                                    <option value="{{ $ing->id }}"
                                                        data-price="{{ $ing->price_per_kg }}">
                                                        {{ $ing->ingredient_name }} (${{ $ing->price_per_kg }}/kg)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" step="0.01" name="ingredients[0][quantity]"
                                                class="form-control text-center ingredient-quantity" required></td>
                                        <td><input type="text" name="ingredients[0][cost]"
                                                class="form-control text-center ingredient-cost" readonly></td>
                                        <td><input type="text" class="form-control text-center ingredient-incidence"
                                                readonly></td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm remove-ingredient"><i
                                                    class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td class="fw-semibold">Total Weight (g)</td>
                                    <td>
                                        <input type="number" id="totalWeightFooter" class="form-control text-center">
                                    </td>
                                    <td>
                                        <input type="text" id="totalCostFooter" name="ingredients_total_cost"
                                            class="form-control text-center" readonly>
                                    </td>
                                    <!-- ▶︎ New: Total incidence cell -->
                                    <td>
                                        <input type="text" id="totalIngredientsIncidence"
                                            class="form-control text-center" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" id="addIngredientBtn"
                                            class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-plus"></i> Add
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>
            </div>

            {{-- 3) Labor --}}
            <div class="card mb-4 border-warning shadow-sm">
                <input type="hidden" name="labor_cost_mode" id="laborCostMode" value="shop">
                <div class="card-header bg-warning text-dark d-flex align-items-center">
                    <i class="bi bi-clock-history fs-4 me-2"></i>
                    <h5 class="mb-0">Labor</h5>
                </div>
                <div class="card-body">

                    <div class="row g-3 mb-3">
                        <div class="col-12">
                            {{-- choose cost mode --}}
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cost_mode" id="costModeShop"
                                    value="shop" checked>
                                <label class="form-check-label" for="costModeShop">Use Shop Cost / Min</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cost_mode" id="costModeExternal"
                                    value="external">
                                <label class="form-check-label" for="costModeExternal">Use External Cost / Min</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="laborTimeInput" class="form-label fw-semibold">Labor Time (min)</label>
                            <input type="number" id="laborTimeInput" name="labor_time_input" class="form-control"
                                value="{{ old('labor_time_input', $isEdit ? $recipe->labour_time_min : '') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label for="costPerMin" class="form-label fw-semibold">Cost per Minute</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="text" id="costPerMin" class="form-control" readonly required>
                            </div>
                        </div>

                        {{-- 1) Cost‐mode selector --}}

                        {{-- 2) Hidden (or readonly) fields that hold the two computed rates --}}
                        <input type="hidden" id="shopCostPerMin"
                            value="{{ optional($laborCost)->shop_cost_per_min ?? '0.0000' }}">
                        <input type="hidden" id="externalCostPerMin"
                            value="{{ optional($laborCost)->external_cost_per_min ?? '0.0000' }}">

                        {{-- 3) Your visible Labor Cost field --}}
                        <div class="col-md-3">
                            <label for="laborCost" class="form-label fw-semibold">Labor Cost ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="text" id="laborCost" name="labor_cost" class="form-control" readonly
                                    required>
                            </div>
                        </div>



                    </div>
                </div>
            </div>


            {{-- 4) Totals & Selling Mode --}}
            <div class="row gx-4 mb-4">
                {{-- Total Expense & Packing --}}
                <div class="col-md-6">
                    <div class="card border-success shadow-sm h-100">
                        <div class="card-header bg-success text-white d-flex align-items-center">
                            <i class="bi bi-calculator fs-4 me-2"></i>
                            <h5 class="mb-0">Total Expense</h5>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center">

                            {{-- Cost per kg before packing --}}
                            <div class="input-group w-75 mb-3">
                                <span class="input-group-text">Cost&nbsp;/&nbsp;kg&nbsp;Before&nbsp;Packing</span>
                                <span class="input-group-text">€</span>
                                <input type="text" id="prodCostKg" name="production_cost_per_kg"
                                    class="form-control text-end" readonly
                                    value="{{ old('production_cost_per_kg', $isEdit ? $recipe->production_cost_per_kg : '') }}">
                            </div>

                            {{-- Packing Cost --}}
                            <div class="input-group w-75 mb-3">
                                <span class="input-group-text">Packing</span><span class="input-group-text">€</span>
                                <input type="number" step="0.01" id="packingCost" name="packing_cost"
                                    class="form-control text-end"
                                    value="{{ old('packing_cost', $isEdit ? $recipe->packing_cost : '0.00') }}">
                            </div>

                            {{-- Cost after packing (total expense) --}}
                            <div class="input-group input-group-lg w-75 mb-3">
                                <span class="input-group-text">Cost&nbsp;/&nbsp;kg&nbsp;After&nbsp;Packing</span>
                                <span class="input-group-text">€</span>
                                <input type="text" id="totalExpense" name="total_expense"
                                    class="form-control fw-bold text-center" readonly required
                                    value="{{ old('total_expense', $isEdit ? $recipe->total_expense : '') }}">
                            </div>

                            {{-- Potential Margin --}}
                            <div class="w-75 text-center">
                                <span class="fw-semibold">Potential Margin:</span>
                                <span id="potentialMargin" class="fw-bold ms-2"></span>
                                <input type="hidden" name="potential_margin" id="potentialMarginInput"
                                    value="{{ old('potential_margin', $isEdit ? $recipe->potential_margin : '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Selling Mode --}}
                <div class="col-md-6">
                    <div class="card border-secondary shadow-sm h-100">
                        <div class="card-header bg-secondary text-white d-flex align-items-center">
                            <i class="bi bi-shop fs-4 me-2"></i>
                            <h5 class="mb-0">Selling Mode</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sell_mode" id="modePiece"
                                        value="piece"
                                        {{ old('sell_mode', $isEdit ? $recipe->sell_mode : 'piece') == 'piece' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="modePiece">Sell by Piece</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="sell_mode" id="modeKg"
                                        value="kg"
                                        {{ old('sell_mode', $isEdit ? $recipe->sell_mode : '') == 'kg' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="modeKg">Sell by Kg</label>
                                </div>
                            </div>

                            {{-- Piece Inputs --}}
                            <div id="pieceInputs">
                                <div class="mb-3">
                                    <label for="totalPieces" class="form-label fw-semibold">Pieces&nbsp;/&nbsp;kg</label>
                                    <input type="number" step="0.01" id="totalPieces" name="total_pieces"
                                        class="form-control"
                                        value="{{ old('total_pieces', $isEdit ? $recipe->total_pieces : '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="weightPerPiece" class="form-label fw-semibold">Weight per
                                        Piece&nbsp;(g)</label>
                                    <input type="text" id="weightPerPiece" class="form-control" readonly>
                                </div>

                                <div class="mb-3">
                                    <label for="pricePerPiece" class="form-label fw-semibold">Selling Price per
                                        Piece&nbsp;($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" step="0.01" id="pricePerPiece"
                                            name="selling_price_per_piece" class="form-control"
                                            value="{{ old('selling_price_per_piece', $isEdit ? $recipe->selling_price_per_piece : '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Kg Inputs --}}
                            <div id="kgInputs" class="d-none">
                                <div class="mb-3">
                                    <label for="totalWeightKg" class="form-label fw-semibold">Total
                                        Weight&nbsp;(g)</label>
                                    <input type="number" id="totalWeightKg" name="recipe_weight" class="form-control"
                                        value="{{ old('recipe_weight', $isEdit ? $recipe->recipe_weight : '') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="pricePerKg" class="form-label fw-semibold">Selling Price per
                                        Kg&nbsp;($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" step="0.01" id="pricePerKg" name="selling_price_per_kg"
                                            class="form-control"
                                            value="{{ old('selling_price_per_kg', $isEdit ? $recipe->selling_price_per_kg : '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 border-info shadow-sm">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <i class="bi bi-plus-circle fs-4 me-2"></i>
                    <h5 class="mb-0">Additions</h5>
                </div>
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="addAsIngredient" name="add_as_ingredient"
                            value="1" {{ old('add_as_ingredient', $isEdit ? 1 : 0) ? 'checked' : '' }}>

                        <label class="form-check-label fw-semibold" for="addAsIngredient">
                            Add this recipe as an <em>ingredient</em>
                        </label>
                        <p class="small text-muted mb-0">
                            If checked, the recipe name will be saved to the ingredients table with
                            a cost / kg equal to "Cost / kg Before Packing".
                        </p>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="text-end">
                <button type="submit" class="btn btn-lg btn-primary"><i
                        class="bi bi-save2 me-2"></i>{{ $isEdit ? 'Update' : 'Save' }} Recipe</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    let idx = 1;

    /* ========= element refs ========= */
    const tableBody        = document.getElementById('ingredientsTable');
    const totalCostIn      = document.getElementById('totalCostFooter');
    const totalWeightFt    = document.getElementById('totalWeightFooter');
    const totalWeightKg    = document.getElementById('totalWeightKg');

    const laborTimeInput   = document.getElementById('laborTimeInput');
    const costPerMinIn     = document.getElementById('costPerMin');
    const laborCostIn      = document.getElementById('laborCost');
    const laborIncidence   = document.getElementById('laborIncidence');

    const packingCostIn    = document.getElementById('packingCost');
    const prodCostKgIn     = document.getElementById('prodCostKg');
    const totalExpenseIn   = document.getElementById('totalExpense');

    const potentialIn      = document.getElementById('potentialMargin');
    const potentialInput   = document.getElementById('potentialMarginInput');

    /* Selling‑mode elements */
    const modePiece        = document.getElementById('modePiece');
    const modeKg           = document.getElementById('modeKg');
    const pieceInputs      = document.getElementById('pieceInputs');
    const kgInputs         = document.getElementById('kgInputs');
    const totalPiecesIn    = document.getElementById('totalPieces');
    const weightPerPiece   = document.getElementById('weightPerPiece');
    const pricePerPiece    = document.getElementById('pricePerPiece');
    const pricePerKg       = document.getElementById('pricePerKg');

    /* Cost‑mode radios and hidden rates */
    const costModeShop     = document.getElementById('costModeShop');
    const costModeExternal = document.getElementById('costModeExternal');
    const shopPM           = document.getElementById('shopCostPerMin');
    const extPM            = document.getElementById('externalCostPerMin');

    /* ========= helper functions ========= */

    function recalcRow(row) {
        const price = parseFloat(row.querySelector('.ingredient-select').selectedOptions[0]?.dataset.price || 0);
        const qty   = parseFloat(row.querySelector('.ingredient-quantity').value || 0);
        row.querySelector('.ingredient-cost').value = ((price/1000)*qty).toFixed(2);
        recalcTotals();
    }

    function calculateIncidence() {
        const sellPrice = modePiece.checked
            ? parseFloat(pricePerPiece.value || 0)
            : parseFloat(pricePerKg.value || 0);
        if (!sellPrice) return;

        document.querySelectorAll('.ingredient-row').forEach(row => {
            const c = parseFloat(row.querySelector('.ingredient-cost').value||0);
            row.querySelector('.ingredient-incidence').value = ((c*100)/sellPrice).toFixed(2)+'%';
        });

        totalIngredientsIncidence.value = ((parseFloat(totalCostIn.value||0)*100)/sellPrice).toFixed(2)+'%';
        laborIncidence.value            = ((parseFloat(laborCostIn.value||0)*100)/sellPrice).toFixed(2)+'%';
    }

    function recalcTotals() {
        let sumC=0, sumW=0;
        document.querySelectorAll('.ingredient-row').forEach(r=>{
            sumC+=parseFloat(r.querySelector('.ingredient-cost').value||0);
            sumW+=parseFloat(r.querySelector('.ingredient-quantity').value||0);
        });
        totalCostIn.value   = sumC.toFixed(2);
        totalWeightFt.value = totalWeightKg.value = sumW;
        recalcLabor();
        calculateIncidence();
    }

    function recalcLabor() {
        // 1) choose rate
        const perMin = parseFloat(
            costModeExternal.checked ? extPM.value : shopPM.value
        )||0;
        costPerMinIn.value = perMin.toFixed(4);

        // 2) compute labor cost
        const mins = parseFloat(laborTimeInput.value||0);
        laborCostIn.value = (mins*perMin).toFixed(2);

        // 3) downstream
        recalcExpense();
        calculateIncidence();
    }

    function recalcExpense() {
        const prodTotal = parseFloat(totalCostIn.value||0) + parseFloat(laborCostIn.value||0);
        const weightGr  = parseFloat(totalWeightKg.value||0);

        prodCostKgIn.value = weightGr
            ? ((prodTotal*1000)/weightGr).toFixed(2)
            : '0.00';

        totalExpenseIn.value = weightGr
            ? (((prodTotal + parseFloat(packingCostIn.value||0))*1000)/weightGr).toFixed(2)
            : '0.00';

        updatePieceWeight();
        recalcMargin();
    }

    function updatePieceWeight() {
        const p = parseFloat(totalPiecesIn.value||0);
        weightPerPiece.value = p ? (1000/p).toFixed(2) : '';
    }

    function recalcMargin() {
        const expKg = parseFloat(totalExpenseIn.value||0);
        let m, label;
        if (modePiece.checked) {
            const ppk = parseFloat(totalPiecesIn.value||0);
            const sell = parseFloat(pricePerPiece.value||0);
            const cost = ppk ? (expKg/ppk) : 0;
            m = sell - cost; label = ' / piece';
        } else {
            const sell = parseFloat(pricePerKg.value||0);
            m = sell - expKg; label = ' / kg';
        }
        potentialIn.innerText    = `$${m.toFixed(2)}${label}`;
        potentialInput.value     = m.toFixed(2);
    }

    function updateMode() {
        if (modePiece.checked) {
            pieceInputs.classList.remove('d-none');
            kgInputs.classList.add('d-none');
        } else {
            pieceInputs.classList.add('d-none');
            kgInputs.classList.remove('d-none');
        }
        recalcMargin();
        calculateIncidence();
    }

    /* ========= event wiring ========= */
    tableBody.addEventListener('input', e => {
        if (e.target.matches('.ingredient-quantity')) recalcRow(e.target.closest('.ingredient-row'));
    });
    tableBody.addEventListener('change', e => {
        if (e.target.matches('.ingredient-select')) recalcRow(e.target.closest('.ingredient-row'));
    });

    document.getElementById('addIngredientBtn').addEventListener('click', () => {
        const first = tableBody.querySelector('.ingredient-row');
        const clone = first.cloneNode(true);
        clone.querySelectorAll('select, input').forEach(el => {
            if (!el.name) return;
            el.name = el.name.replace(/\[\d+\]/, `[${idx}]`);
            el.tagName==='SELECT' ? el.selectedIndex=0 : el.value = el.classList.contains('ingredient-quantity') ? '0' : '';
        });
        idx++; tableBody.appendChild(clone);
    });

    tableBody.addEventListener('click', e => {
        if (e.target.closest('.remove-ingredient') && tableBody.children.length>1) {
            e.target.closest('.ingredient-row').remove();
            recalcTotals();
        }
    });

    laborTimeInput.addEventListener('input', recalcLabor);
    packingCostIn.addEventListener('input', recalcExpense);
    pricePerPiece.addEventListener('input', recalcMargin);
    pricePerKg.addEventListener('input', recalcMargin);
    totalPiecesIn.addEventListener('input', () => { updatePieceWeight(); recalcMargin(); });
    totalWeightFt.addEventListener('input', () => { totalWeightKg.value = totalWeightFt.value; recalcExpense(); });
    totalWeightKg.addEventListener('input', () => { totalWeightFt.value = totalWeightKg.value; recalcExpense(); });

    modePiece.addEventListener('change', () => { updateMode(); }); 
    modeKg.addEventListener('change', () => { updateMode(); });

    /* cost‑mode only triggers labor recalc & downstream */
    costModeShop.addEventListener('change', recalcLabor);
    costModeExternal.addEventListener('change', recalcLabor);

    /* ========= init ========= */
    updateMode();
    recalcTotals();
});

document.addEventListener('DOMContentLoaded', () => {
    const hiddenMode = document.getElementById('laborCostMode');
    const costModeShop = document.getElementById('costModeShop');
    const costModeExternal = document.getElementById('costModeExternal');

    // Update the hidden input value based on selected radio
    function updateHiddenMode() {
        hiddenMode.value = costModeExternal.checked ? 'external' : 'shop';
    }

    // Trigger on load + when user changes selection
    updateHiddenMode();
    costModeShop.addEventListener('change', updateHiddenMode);
    costModeExternal.addEventListener('change', updateHiddenMode);
});

</script>
@endsection


