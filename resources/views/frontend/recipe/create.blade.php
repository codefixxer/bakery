@extends('frontend.layouts.app')

@section('title', 'Create Recipe')
@php
    $sectionHeaderStyle = 'style="background-color: #041930; color: #e2ae76;"';
@endphp
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

            <div class="card mb-4 border-primary shadow-sm">
                <div class="card-header d-flex align-items-center" style="background-color: #041930; color: #e2ae76;">
                    <i class="bi bi-journal-text fs-4 me-2" style="color: #e2ae76;"></i>
                    <h5 class="mb-0" style="color: #e2ae76;">Recipe Details</h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="recipeName" class="form-label fw-semibold">Name</label>
                            <input type="text" id="recipeName" name="recipe_name" class="form-control"
                                placeholder="Chocolate Cake"
                                value="{{ old('recipe_name', $isEdit ? $recipe->recipe_name : '') }}" required>
                        </div>
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

            <div class="card mb-4 border-info shadow-sm">
                <div class="card-header d-flex align-items-center" style="background-color: #041930;">
                    <i class="bi bi-list-ul fs-4 me-2" style="color: #e2ae76;"></i>
                    <h5 class="mb-0" style="color: #e2ae76;">Ingredients</h5>
                    <button type="button" class="btn btn-outline-light ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addIngredientModal">
                        <i class="bi bi-plus-lg"></i> New Ingredient
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ingredient</th>
                                    <th class="text-center">Qty&nbsp;(g)</th>
                                    <th class="text-center">Cost&nbsp;($)</th>
                                    <th class="text-center">Incidence&nbsp;(%)</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="ingredientsTable">
                                @if ($isEdit && $recipe->ingredients->isNotEmpty())
                                    @foreach ($recipe->ingredients as $i => $line)
                                        <tr class="ingredient-row">
                                            <td>
                                                <select name="ingredients[{{ $i }}][id]"
                                                    class="form-select ingredient-select" required>
                                                    <option value="" selected>Select ingredient…</option>
                                                    @foreach ($ingredients as $ing)
                                                        <option value="{{ $ing->id }}"
                                                            data-price="{{ $ing->price_per_kg }}"
                                                            {{ $ing->id == $line->ingredient_id ? 'selected' : '' }}>
                                                            {{ $ing->ingredient_name }} (€{{ $ing->price_per_kg }}/kg)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" step="0.01"
                                                    name="ingredients[{{ $i }}][quantity]"
                                                    class="form-control text-center ingredient-quantity"
                                                    value="{{ old("ingredients.$i.quantity", $line->quantity_g) }}"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="text" name="ingredients[{{ $i }}][cost]"
                                                    class="form-control text-center ingredient-cost" readonly
                                                    value="{{ old("ingredients.$i.cost", $line->cost) }}">
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
                                                        {{ $ing->ingredient_name }} (€{{ $ing->price_per_kg }}/kg)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="ingredients[0][quantity]"
                                                class="form-control text-center ingredient-quantity" required>
                                        </td>
                                        <td>
                                            <input type="text" name="ingredients[0][cost]"
                                                class="form-control text-center ingredient-cost" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-center ingredient-incidence"
                                                readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-ingredient">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td class="fw-semibold">Total Weight (g)</td>
                                    <td>
                                        <input type="number" id="totalWeightFooter" class="form-control text-center"
                                            readonly>
                                        <input type="hidden" name="ingredients_total_weight"
                                            id="ingredientsTotalWeightHidden">
                                    </td>
                                    <td>
                                        <input type="text" id="totalCostFooter" name="ingredients_total_cost"
                                            class="form-control text-center" readonly>
                                    </td>
                                    <td>
                                        <input type="text" id="totalIncidenceFooter"
                                            class="form-control text-center fw-bold" readonly>
                                    </td>
                                    <td class="text-center">
                                        <button id="addIngredientBtn" class="btn btn-outline-success btn-sm">
                                            <i class="bi bi-plus"></i> Add
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Weight w/ Loss (g)</td>
                                    <td>
                                        <input type="number" id="weightWithLoss" name="weight_with_loss"
                                            class="form-control text-center">
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mb-4 border-warning shadow-sm">
                <input type="hidden" id="shopRate" value="{{ optional($laborCost)->shop_cost_per_min ?? 0 }}">
                <input type="hidden" id="externalRate" value="{{ optional($laborCost)->external_cost_per_min ?? 0 }}">
                <div class="card-header d-flex align-items-center" style="background-color: #041930;">
                    <i class="bi bi-clock-history fs-4 me-2" style="color: #e2ae76;"></i>
                    <h5 class="mb-0" style="color: #e2ae76;">Labor</h5>
                </div>

                <div class="card-body">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="labor_cost_mode" id="costModeShop"
                            value="shop"
                            {{ old('labor_cost_mode', $isEdit ? $recipe->labor_cost_mode : 'shop') == 'shop' ? 'checked' : '' }}>
                        <label class="form-check-label" for="costModeShop">Use Shop Cost / Min</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="labor_cost_mode" id="costModeExternal"
                            value="external"
                            {{ old('labor_cost_mode', $isEdit ? $recipe->labor_cost_mode : 'shop') == 'external' ? 'checked' : '' }}>
                        <label class="form-check-label" for="costModeExternal">Use External Cost / Min</label>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <label for="laborTimeInput" class="form-label fw-semibold">Labor Time (min)</label>
                            <input type="number" id="laborTimeInput" name="labor_time_input" class="form-control"
                                min="0"
                                value="{{ old('labor_time_input', $isEdit ? $recipe->labour_time_min : 0) }}">
                        </div>
                        <div class="col-md-3">
                            <label for="costPerMin" class="form-label fw-semibold">Cost per Minute (€)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="text" id="costPerMin" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="laborCost" class="form-label fw-semibold">Labor Cost (€)</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="text" id="laborCost" name="labor_cost" class="form-control" readonly
                                    value="{{ old('labor_cost', $isEdit ? $recipe->labour_cost : '') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="laborIncidence" class="form-label fw-semibold">Labor Incidence (%)</label>
                            <input type="text" id="laborIncidence" class="form-control text-center" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gx-4 mb-4">
                <div class="col-md-6">
                    <div class="card border-success shadow-sm h-100">
                        <div class="card-header d-flex align-items-center" style="background-color: #041930;">
                            <i class="bi bi-calculator fs-4 me-2" style="color: #e2ae76;"></i>
                            <h5 class="mb-0" style="color: #e2ae76;">Total Expense</h5>
                        </div>

                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="input-group w-75 mb-3">
                                <span class="input-group-text">Cost / kg Before Packing</span>
                                <span class="input-group-text">€</span>
                                <input type="text" id="prodCostKg" name="production_cost_per_kg"
                                    class="form-control text-end" readonly>
                            </div>
                            <div class="input-group w-75 mb-3">
                                <span class="input-group-text">Packing</span>
                                <span class="input-group-text">€</span>
                                <input type="number" step="0.01" id="packingCost" name="packing_cost"
                                    class="form-control text-end" value="0">
                            </div>
                            <div class="input-group input-group-lg w-75 mb-3">
                                <span class="input-group-text">Cost / kg After Packing</span>
                                <span class="input-group-text">€</span>
                                <input type="text" id="totalExpense" name="total_expense"
                                    class="form-control fw-bold text-center" readonly>
                            </div>
                            <div class="w-75 text-center">
                                <span class="fw-semibold">Potential Margin:</span>
                                <span id="potentialMargin" class="fw-bold ms-2"></span>
                                <input type="hidden" name="potential_margin" id="potentialMarginInput">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-secondary shadow-sm h-100">
                        <div class="card-header d-flex align-items-center" style="background-color: #041930;">
                            <i class="bi bi-shop fs-4 me-2" style="color: #e2ae76;"></i>
                            <h5 class="mb-0" style="color: #e2ae76;">Selling Mode</h5>
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
                            <div id="pieceInputs">
                                <div class="mb-3">
                                    <label for="totalPieces" class="form-label fw-semibold">Pieces / kg</label>
                                    <input type="number" id="totalPieces" name="total_pieces" class="form-control"
                                        value="{{ old('total_pieces', $isEdit ? $recipe->total_pieces : '') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="weightPerPiece" class="form-label fw-semibold">
                                        Weight per Piece&nbsp;(g)
                                    </label>
                                    <input type="text" id="weightPerPiece" class="form-control" readonly
                                        value="{{ old(
                                            'weight_per_piece',
                                            $isEdit && $recipe->total_pieces > 0 ? number_format(1000 / $recipe->total_pieces, 2) : '',
                                        ) }}">
                                    <div class="form-text text-muted">
                                        Weight per piece = 1 000 g ÷ Pieces / kg
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="pricePerPiece" class="form-label fw-semibold">Selling Price per Piece
                                        (€)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" step="0.01" id="pricePerPiece"
                                            name="selling_price_per_piece" class="form-control"
                                            value="{{ old('selling_price_per_piece') !== null
                                                ? old('selling_price_per_piece')
                                                : ($isEdit
                                                    ? $recipe->selling_price_per_piece
                                                    : '0') }}">
                                    </div>
                                </div>
                            </div>
                            <div id="kgInputs" class="d-none">
                                <div class="mb-3">
                                    <label for="pricePerKg" class="form-label fw-semibold">Selling Price per
                                        Kg&nbsp;($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">€</span>
                                        <input type="number" step="0.01" id="pricePerKg" name="selling_price_per_kg"
                                            class="form-control"
                                            value="{{ old('selling_price_per_kg', $isEdit ? $recipe->selling_price_per_kg : '0') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="vatRate" class="form-label fw-semibold">VAT Rate</label>
                                    <select id="vatRate" name="vat_rate" class="form-select">
                                        <option value="0">No VAT</option>
                                        <option value="4">4%</option>
                                        <option value="10">10%</option>
                                        <option value="22" selected>22%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4 border-info shadow-sm">
                <div class="card-header d-flex align-items-center" style="background-color: #041930;">
                    <i class="bi bi-plus-circle fs-4 me-2" style="color: #e2ae76;"></i>
                    <h5 class="mb-0" style="color: #e2ae76;">Additions</h5>
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

            <div class="text-end">
                <button type="submit" class="btn btn-lg btn-primary"><i
                        class="bi bi-save2 me-2"></i>{{ $isEdit ? 'Update' : 'Save' }} Recipe</button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="addIngredientModal" tabindex="-1" aria-labelledby="addIngredientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addIngredientModalLabel">Add New Ingredient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addIngredientForm" action="{{ route('ingredients.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="ingredientNameModal" class="form-label">Ingredient Name</label>
                            <input type="text" id="ingredientNameModal" name="ingredient_name" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="pricePerKgModal" class="form-label">Price per kg (€)</label>
                            <input type="number" id="pricePerKgModal" name="price_per_kg" class="form-control"
                                step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Ingredient</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const addForm            = document.getElementById('addIngredientForm');
    const modalEl            = document.getElementById('addIngredientModal');
    const vatRateEl          = document.getElementById('vatRate');
    const shopRateEl         = document.getElementById('shopRate');
    const externalRateEl     = document.getElementById('externalRate');
    const costModeShop       = document.getElementById('costModeShop');
    const costModeExternal   = document.getElementById('costModeExternal');
    const laborTimeInput     = document.getElementById('laborTimeInput');
    const costPerMinIn       = document.getElementById('costPerMin');
    const laborCostIn        = document.getElementById('laborCost');
    const laborIncidenceIn   = document.getElementById('laborIncidence');
    const pricePerPiece      = document.getElementById('pricePerPiece');
    const pricePerKg         = document.getElementById('pricePerKg');
    const modePiece          = document.getElementById('modePiece');
    const modeKg             = document.getElementById('modeKg');
    const totalPiecesIn      = document.getElementById('totalPieces');
    const weightPerPieceIn   = document.getElementById('weightPerPiece');
    const weightWithLossIn   = document.getElementById('weightWithLoss');
    const tableBody          = document.getElementById('ingredientsTable');
    const totalCostIn        = document.getElementById('totalCostFooter');
    const totalIncidenceIn   = document.getElementById('totalIncidenceFooter');
    const totalWeightFt      = document.getElementById('totalWeightFooter');
    const hiddenTotalWt      = document.getElementById('ingredientsTotalWeightHidden');
    const packingCostIn      = document.getElementById('packingCost');
    const prodCostKgIn       = document.getElementById('prodCostKg');
    const totalExpenseIn     = document.getElementById('totalExpense');
    const potentialMargin    = document.getElementById('potentialMargin');
    const potentialInput     = document.getElementById('potentialMarginInput');
    let weightLossTouched = false;
    let idx = {{ isset($recipe) ? $recipe->ingredients->count() : 1 }};

    // 1) AJAX “Add Ingredient”
    addForm.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = new FormData(addForm);

        try {
            const res = await fetch(addForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: fd
            });
            const json = await res.json();

            if (!res.ok) {
                alert(
                    json.errors ?
                    Object.values(json.errors).flat().join('\n') :
                    'Failed to save ingredient.'
                );
                return;
            }

            // build new <option>
            const opt = document.createElement('option');
            opt.value = json.id;
            opt.textContent = `${json.ingredient_name} (€${json.price_per_kg}/kg)`;
            opt.dataset.price = json.price_per_kg;

            document.querySelectorAll('.ingredient-select').forEach(sel => {
                // remember current selection
                const prev = sel.value;

                // insert in sorted order
                const clone = opt.cloneNode(true);
                const newName = json.ingredient_name.toLowerCase().trim();
                let inserted = false;
                for (let i = 1; i < sel.options.length; i++) {
                    const existingName = sel.options[i].textContent
                        .split('(')[0]
                        .toLowerCase()
                        .trim();
                    if (newName.localeCompare(existingName) < 0) {
                        sel.insertBefore(clone, sel.options[i]);
                        inserted = true;
                        break;
                    }
                }
                if (!inserted) {
                    sel.appendChild(clone);
                }

                // restore placeholder at front
                const placeholder = sel.querySelector('option[value=""]');
                sel.removeChild(placeholder);
                sel.insertBefore(placeholder, sel.firstChild);

                // restore previous selection
                if (prev) {
                    sel.value = prev;
                    if (sel.value !== prev) sel.selectedIndex = 0;
                } else {
                    sel.selectedIndex = 0;
                }
            });

            // hide modal & reset form
            bootstrap.Modal.getInstance(modalEl).hide();
            addForm.reset();

        } catch (err) {
            console.error(err);
            alert('Unexpected error while saving ingredient.');
        }
    });

    // 2) gross → net (VAT)
    function netPrice(gross) {
        const vat = parseFloat(vatRateEl.value) || 0;
        return gross / (1 + vat / 100);
    }

    // 3) grams per piece — NOW based on total weight WITH LOSS
    function calcWeightPerPiece() {
        const pcs    = parseFloat(totalPiecesIn.value)    || 0;
        const totalG = parseFloat(weightWithLossIn.value) || 0;
        weightPerPieceIn.value = pcs > 0
            ? (totalG / pcs).toFixed(2)
            : '';
    }

    // 4) cost-per-min toggle
    function updateCostPerMin() {
        const rate = costModeShop.checked
            ? parseFloat(shopRateEl.value)     || 0
            : parseFloat(externalRateEl.value) || 0;
        costPerMinIn.value = rate.toFixed(4);
        updateLaborCost();
    }

    // 5) labor cost
    function updateLaborCost() {
        const mins = parseFloat(laborTimeInput.value) || 0;
        const rate = parseFloat(costPerMinIn.value)   || 0;
        laborCostIn.value = (mins * rate).toFixed(2);
        calculateLaborIncidence();
        recalcTotals();
    }

    // 6) labor incidence %
    function calculateLaborIncidence() {
        const lc   = parseFloat(laborCostIn.value)        || 0;
        const g    = parseFloat(weightWithLossIn.value)   || 0;
        const kg   = g / 1000;
        const sell = modePiece.checked
            ? (parseFloat(totalPiecesIn.value) || 0) * (parseFloat(pricePerPiece.value) || 0)
            : parseFloat(pricePerKg.value) || 0;

        laborIncidenceIn.value = (kg > 0 && sell > 0
            ? ((lc / kg) / netPrice(sell)) * 100
            : 0
        ).toFixed(2);
    }

    // 7) recalc one ingredient row
    function recalcRow(row) {
        const price = parseFloat(
            row.querySelector('.ingredient-select').selectedOptions[0]?.dataset.price
        ) || 0;
        const qty  = parseFloat(
            row.querySelector('.ingredient-quantity').value
        ) || 0;
        const cost = price / 1000 * qty;
        row.querySelector('.ingredient-cost').value = cost.toFixed(2);

        const g    = parseFloat(weightWithLossIn.value) || 0;
        const kg   = g / 1000;
        const sell = modePiece.checked
            ? (parseFloat(totalPiecesIn.value) || 0) * (parseFloat(pricePerPiece.value) || 0)
            : parseFloat(pricePerKg.value) || 0;

        row.querySelector('.ingredient-incidence').value = (
            kg > 0 && sell > 0
                ? ((cost / kg) / netPrice(sell)) * 100
                : 0
        ).toFixed(2);
    }

    // 8) recalc all ingredient totals
    function recalcTotals() {
        let sW = 0, sC = 0, sI = 0;
        document.querySelectorAll('.ingredient-row').forEach(r => {
            sW += parseFloat(r.querySelector('.ingredient-quantity').value) || 0;
            sC += parseFloat(r.querySelector('.ingredient-cost').value)     || 0;
            sI += parseFloat(r.querySelector('.ingredient-incidence').value)|| 0;
        });

        totalWeightFt.value  = sW;
        totalCostIn.value    = sC.toFixed(2);
        totalIncidenceIn.value = sI.toFixed(2);

        if (!weightLossTouched) weightWithLossIn.value = sW;
        hiddenTotalWt.value  = weightWithLossIn.value;

        recalcExpense();
        recalcMargin();
    }

    // 9) cost before/after packing — NOW packing cost is divided by number of pieces
    function recalcExpense() {
        const ing = parseFloat(totalCostIn.value)    || 0;
        const lab = parseFloat(laborCostIn.value)    || 0;
        const raw = ing + lab;
        const pack = parseFloat(packingCostIn.value) || 0;

        if (modePiece.checked) {
            const pcs    = parseFloat(totalPiecesIn.value) || 0;
            const before = pcs > 0 ? raw / pcs : 0;
            const packPerPiece = pcs > 0 ? pack / pcs : 0;
            prodCostKgIn.value = before.toFixed(2);
            totalExpenseIn.value = (before + packPerPiece).toFixed(2);
        } else {
            const g    = parseFloat(weightWithLossIn.value) || 0;
            const kg   = g / 1000;
            const before = kg > 0 ? raw / kg : 0;
            prodCostKgIn.value = before.toFixed(2);
            totalExpenseIn.value = (before + pack).toFixed(2);
        }
    }

    // 10) potential margin
    function recalcMargin() {
        const ingTot  = parseFloat(totalCostIn.value)    || 0;
        const labTot  = parseFloat(laborCostIn.value)    || 0;
        const packIn  = parseFloat(packingCostIn.value)  || 0;

        if (modePiece.checked) {
            const sellP = parseFloat(pricePerPiece.value) || 0;
            const netSP = netPrice(sellP);
            const pcs   = parseFloat(totalPiecesIn.value) || 0;
            const ingPP = pcs > 0 ? ingTot / pcs : 0;
            const labPP = pcs > 0 ? labTot / pcs : 0;
            const m     = netSP - ingPP - labPP - packIn;
            const pct   = netSP > 0 ? (m * 100 / netSP) : 0;

            potentialMargin.innerText = `€${m.toFixed(2)} (${pct.toFixed(2)}%) / piece`;
            potentialInput.value      = m.toFixed(2);
        } else {
            const g     = parseFloat(weightWithLossIn.value) || 0;
            const kg    = g / 1000;
            const sellK = parseFloat(pricePerKg.value)        || 0;
            const netSK = netPrice(sellK);
            const ingKg = kg > 0 ? ingTot / kg : 0;
            const labKg = kg > 0 ? labTot / kg : 0;
            const m     = netSK - ingKg - labKg - packIn;
            const pct   = netSK > 0 ? (m * 100 / netSK) : 0;

            potentialMargin.innerText = `€${m.toFixed(2)} (${pct.toFixed(2)}%) / kg`;
            potentialInput.value      = m.toFixed(2);
        }
    }

    // 11) mode toggle & label swapping (also re-calc gram-per-piece)
    function updateMode() {
        const beforeLabel = prodCostKgIn.closest('.input-group')
                              .querySelector('.input-group-text');
        const afterLabel  = totalExpenseIn.closest('.input-group')
                              .querySelector('.input-group-text');

        if (modePiece.checked) {
            beforeLabel.textContent = 'Cost / pc Before Packing';
            afterLabel .textContent = 'Cost / pc After Packing';
        } else {
            beforeLabel.textContent = 'Cost / kg Before Packing';
            afterLabel .textContent = 'Cost / kg After Packing';
        }

        document.getElementById('pieceInputs')
            .classList.toggle('d-none', !modePiece.checked);
        document.getElementById('kgInputs')
            .classList.toggle('d-none',  modePiece.checked);

        recalcTotals();
        if (modePiece.checked) {
            calcWeightPerPiece();
        }
    }

    // ——————————————
    // Wire up all listeners
    // ——————————————

    costModeShop.addEventListener('change',  updateCostPerMin);
    costModeExternal.addEventListener('change', updateCostPerMin);
    laborTimeInput.addEventListener('input',  updateLaborCost);
    vatRateEl.addEventListener('change',      recalcTotals);
    packingCostIn.addEventListener('input',   recalcExpense);

    // **Re-calc gram-per-piece whenever total weight changes**
    weightWithLossIn.addEventListener('input', () => {
        weightLossTouched = true;
        recalcTotals();
        calcWeightPerPiece();
    });

    pricePerPiece.addEventListener('input',    recalcMargin);
    pricePerKg.addEventListener('input',       recalcMargin);

    totalPiecesIn.addEventListener('input',    () => {
        calcWeightPerPiece();
        updateMode();
    });
    modePiece.addEventListener('change',       () => {
        calcWeightPerPiece();
        updateMode();
    });
    modeKg.addEventListener('change',          updateMode);

    ['input','change'].forEach(evt => {
        tableBody.addEventListener(evt, e => {
            if (e.target.matches('.ingredient-select, .ingredient-quantity')) {
                const row = e.target.closest('.ingredient-row');
                recalcRow(row);
                recalcTotals();
            }
        });
    });

    document.getElementById('addIngredientBtn').addEventListener('click', e => {
        e.preventDefault();
        const first = tableBody.querySelector('.ingredient-row');
        const clone = first.cloneNode(true);
        const newIdx = idx++;
        clone.querySelectorAll('select[name], input[name]').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${newIdx}]`);
            if (el.tagName === 'SELECT') el.selectedIndex = 0;
            else el.value = el.classList.contains('ingredient-quantity') ? '0' : '';
        });
        tableBody.appendChild(clone);
        recalcRow(clone);
        recalcTotals();
    });

    tableBody.addEventListener('click', e => {
        if (e.target.closest('.remove-ingredient') 
            && tableBody.children.length > 1) {
            e.target.closest('.ingredient-row').remove();
            recalcTotals();
        }
    });

    // ——————————————
    // Initial bootstrap
    // ——————————————
    document.querySelectorAll('.ingredient-row').forEach(r => recalcRow(r));
    calcWeightPerPiece();
    updateMode();
    updateCostPerMin();
    recalcTotals();
});
</script>

@endsection

