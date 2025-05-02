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


                    {{-- Add New Ingredient Button --}}
                    <button type="button" class="btn btn-outline-light ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addIngredientModal">
                        <i class="bi bi-plus-lg"></i> New Ingredient
                    </button>

                    {{-- Modal to Add Ingredient --}}



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
                                    <td class="fw-semibold">Total Weight (g)</td>
                                    <td>
                                        <input
                                        type="number"
                                        id="totalWeightFooter"
                                        class="form-control text-center"
                                        readonly>
                                      <!-- and post it via this hidden -->
                                      <input
                                        type="hidden"
                                        name="ingredients_total_weight"
                                        id="ingredientsTotalWeightHidden">
                                    </td>
                                    </td>
                                    <td>
                                        <input type="text" id="totalCostFooter" name="ingredients_total_cost"
                                            class="form-control text-center" readonly>
                                    </td>
                                    <td>
                                        <input type="text" id="totalIngredientsIncidence"
                                            class="form-control text-center" readonly>
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

            {{-- 3) Labor --}}
            <div class="card mb-4 border-warning shadow-sm">
                <input type="hidden" id="shopRate" value="{{ optional($laborCost)->shop_cost_per_min ?? 0 }}">
                <input type="hidden" id="externalRate" value="{{ optional($laborCost)->external_cost_per_min ?? 0 }}">

                <div class="card-header bg-warning text-dark d-flex align-items-center">
                    <i class="bi bi-clock-history fs-4 me-2"></i>
                    <h5 class="mb-0">Labor</h5>
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
                            <label for="laborIncidence" class="form-label fw-semibold">Incidence (%)</label>
                            <input type="text" id="laborIncidence" name="labor_incidence" class="form-control"
                                readonly value="{{ old('labor_incidence', $isEdit ? $recipe->labor_incidence : '') }}">
                            <div class="form-text small">= (Labor Cost × 100) / Selling Price</div>
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
                                <span class="input-group-text">Cost / kg Before Packing</span>
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
                                                    : '') }}">
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



                            <!-- … inside your Selling Mode card, before the piece/kg radios … -->
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


    {{-- … your modal markup stays the same … --}}
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
        document.addEventListener('DOMContentLoaded', function() {
            const shopRateEl = document.getElementById('shopRate');
            const externalRateEl = document.getElementById('externalRate');
            const costModeShop = document.getElementById('costModeShop');
            const costModeExt = document.getElementById('costModeExternal');
            const laborMinInput = document.getElementById('laborTimeInput');
            const costPerMin = document.getElementById('costPerMin');
            const laborCost = document.getElementById('laborCost');
            const laborIncidence = document.getElementById('laborIncidence');

            function updateCostPerMin() {
                const rate = costModeShop.checked ?
                    parseFloat(shopRateEl.value) || 0 :
                    parseFloat(externalRateEl.value) || 0;
                costPerMin.value = rate.toFixed(4);
                updateLaborCost();
            }

            function updateLaborCost() {
                const mins = parseFloat(laborMinInput.value) || 0;
                const rate = parseFloat(costPerMin.value) || 0;
                const total = mins * rate;
                laborCost.value = total.toFixed(2);
                updateLaborIncidence();
            }

            function updateLaborIncidence() {
                // if you have a selling price field, replace below:
                const sellingPrice = parseFloat(document.getElementById('pricePerKg')?.value || document
                    .getElementById('pricePerPiece')?.value || 0);
                if (sellingPrice > 0) {
                    laborIncidence.value = ((parseFloat(laborCost.value) * 100) / sellingPrice).toFixed(2) + '%';
                } else {
                    laborIncidence.value = '';
                }
            }

            // wire up events
            [costModeShop, costModeExt].forEach(radio => radio.addEventListener('change', updateCostPerMin));
            laborMinInput.addEventListener('input', updateLaborCost);

            // init
            updateCostPerMin();
        });
    </script>


<script>
    document.addEventListener('DOMContentLoaded', () => {
      const addIngredientForm = document.getElementById('addIngredientForm');
      const ingredientSelects = document.querySelectorAll('.ingredient-select');
      const modalEl           = document.getElementById('addIngredientModal');
    
      addIngredientForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(addIngredientForm);
    
        try {
          const response = await fetch(addIngredientForm.action, {
            method: 'POST',
            headers: {
              // Tell Laravel this is an AJAX/JSON request
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            body: formData
          });
    
          const result = await response.json();
    
          if (response.ok) {
            // 1) append to every ingredient dropdown
            ingredientSelects.forEach(select => {
              const opt = document.createElement('option');
              opt.value = result.id;
              opt.textContent = `${result.ingredient_name} (€${result.price_per_kg}/kg)`;
              select.appendChild(opt);
            });
    
            // 2) select the newly created ingredient in the first row
            ingredientSelects[0].value = result.id;
    
            // 3) close the modal
            const bsModal = bootstrap.Modal.getInstance(modalEl);
            bsModal.hide();
          } else {
            // If validation failed, Laravel returns a 422 with JSON.errors
            const msg = result.errors
              ? Object.values(result.errors).flat().join('\n')
              : 'Failed to add ingredient';
            alert(msg);
          }
        } catch (err) {
          console.error(err);
          alert('Error occurred while adding ingredient');
        }
      });
    });
    </script>
    
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // cache all your elements
        const tableBody        = document.getElementById('ingredientsTable');
        const totalCostIn      = document.getElementById('totalCostFooter');
        const totalWeightFt    = document.getElementById('totalWeightFooter');
        const totalWeightKg    = document.getElementById('totalWeightKg');
        const weightWithLossIn = document.getElementById('weightWithLoss');
        const hiddenTotalWt    = document.getElementById('ingredientsTotalWeightHidden'); // the hidden input you added
        const laborTimeInput   = document.getElementById('laborTimeInput');
        const costPerMinIn     = document.getElementById('costPerMin');
        const laborCostIn      = document.getElementById('laborCost');
        const laborIncidence   = document.getElementById('laborIncidence');
        const packingCostIn    = document.getElementById('packingCost');
        const prodCostKgIn     = document.getElementById('prodCostKg');
        const totalExpenseIn   = document.getElementById('totalExpense');
        const potentialIn      = document.getElementById('potentialMargin');
        const potentialInput   = document.getElementById('potentialMarginInput');
        const modePiece        = document.getElementById('modePiece');
        const modeKg           = document.getElementById('modeKg');
        const totalPiecesIn    = document.getElementById('totalPieces');
        const weightPerPiece   = document.getElementById('weightPerPiece');
        const pricePerPiece    = document.getElementById('pricePerPiece');
        const pricePerKg       = document.getElementById('pricePerKg');
        const costModeShop     = document.getElementById('costModeShop');
        const costModeExternal = document.getElementById('costModeExternal');
        const shopPM           = document.getElementById('shopRate');
        const extPM            = document.getElementById('externalRate');
        const vatRate          = document.getElementById('vatRate');
        let weightLossTouched  = false;
        let idx                = {{ isset($recipe) ? $recipe->ingredients->count() : 1 }};
    
        // if user edits weight-with-loss manually
        weightWithLossIn.addEventListener('input', () => {
            weightLossTouched = true;
            recalcExpense();
            calculateIncidence();
        });
    
        function netPrice() {
            const gross = modePiece.checked
                ? parseFloat(pricePerPiece.value) || 0
                : parseFloat(pricePerKg.value)   || 0;
            const v = (parseFloat(vatRate.value) || 0) / 100;
            return v ? gross / (1 + v) : gross;
        }
        
    
        function recalcRow(row) {
            const price = parseFloat(row.querySelector('.ingredient-select')
                                   .selectedOptions[0]?.dataset.price) || 0;
            const qty   = parseFloat(row.querySelector('.ingredient-quantity').value) || 0;
            const cost  = (price / 1000) * qty;
            row.querySelector('.ingredient-cost').value = cost.toFixed(2);
        }
    
        function calculateIncidence() {
            const n = netPrice();
            if (!n) return;
            let sumCost = 0;
            document.querySelectorAll('.ingredient-row').forEach(r => {
                const c = parseFloat(r.querySelector('.ingredient-cost').value) || 0;
                sumCost += c;
                r.querySelector('.ingredient-incidence').value =
                    ((c * 100) / n).toFixed(2) + '%';
            });
            document.getElementById('totalIngredientsIncidence').value =
                ((sumCost * 100) / n).toFixed(2) + '%';
    
            const lc = parseFloat(laborCostIn.value) || 0;
            laborIncidence.value = ((lc * 100) / n).toFixed(2) + '%';
        }
    
        function recalcTotals() {
            let sC = 0, sW = 0;
            document.querySelectorAll('.ingredient-row').forEach(r => {
                sC += parseFloat(r.querySelector('.ingredient-cost').value)    || 0;
                sW += parseFloat(r.querySelector('.ingredient-quantity').value) || 0;
            });
            totalCostIn.value  = sC.toFixed(2);
            totalWeightFt.value = totalWeightKg.value = sW;
    
            if (!weightLossTouched) {
                weightWithLossIn.value = sW;
            }
            // keep hidden field in sync with the actual yield
            hiddenTotalWt.value = weightWithLossIn.value;
    
            recalcLabor();
            calculateIncidence();
        }
    
        function recalcLabor() {
            const rate = parseFloat(costModeExternal.checked ? extPM.value : shopPM.value) || 0;
            costPerMinIn.value = rate.toFixed(4);
            const mins = parseFloat(laborTimeInput.value) || 0;
            laborCostIn.value = (mins * rate).toFixed(2);
    
            recalcExpense();
            calculateIncidence();
        }
    
        // recalc based on actual cooked yield (weightWithLossIn)
        function recalcExpense() {
            const ingredientCost = parseFloat(totalCostIn.value) || 0;
            const laborVal       = parseFloat(laborCostIn.value)  || 0;
            const rawCost        = ingredientCost + laborVal;
    
            const yieldG = parseFloat(weightWithLossIn.value) || 0;
            hiddenTotalWt.value = yieldG;
    
            // Cost/kg before packing = (rawCost * 1000g) / yieldG
            prodCostKgIn.value = yieldG
                ? ((rawCost * 1000) / yieldG).toFixed(2)
                : '0.00';
    
            // Cost/kg after packing
            const packingVal      = parseFloat(packingCostIn.value) || 0;
            totalExpenseIn.value = yieldG
                ? (((rawCost + packingVal) * 1000) / yieldG).toFixed(2)
                : '0.00';
    
            updatePieceWeight();
            recalcMargin();
        }
    
        function updatePieceWeight() {
            const pcs = parseFloat(totalPiecesIn.value) || 0;
            weightPerPiece.value = pcs ? (1000 / pcs).toFixed(2) : '';
        }
    
        function recalcMargin() {
            const exp = parseFloat(totalExpenseIn.value) || 0;
            const np  = netPrice();
            let mText;
            if (modePiece.checked) {
                const pcs = parseFloat(totalPiecesIn.value) || 0;
                const c   = pcs ? exp / pcs : 0;
                mText     = (np - c).toFixed(2) + ' / piece';
            } else {
                mText     = (np - exp).toFixed(2) + ' / kg';
            }
            potentialIn.innerText = `$${mText}`;
            potentialInput.value  = parseFloat(mText) || 0;
        }
    
        function updateMode() {
            document.getElementById('pieceInputs')
                    .classList.toggle('d-none', !modePiece.checked);
            document.getElementById('kgInputs')
                    .classList.toggle('d-none',  modePiece.checked);
            recalcMargin();
            calculateIncidence();
        }
    
        // ── EVENTS ──
    
        tableBody.addEventListener('input', e => {
            if (e.target.matches('.ingredient-quantity')) {
                const row = e.target.closest('.ingredient-row');
                recalcRow(row);
                recalcTotals();
            }
        });
        tableBody.addEventListener('change', e => {
            if (e.target.matches('.ingredient-select')) {
                const row = e.target.closest('.ingredient-row');
                recalcRow(row);
                recalcTotals();
            }
        });
    
        packingCostIn.addEventListener('input', recalcExpense);
        laborTimeInput.addEventListener('input', recalcLabor);
        costModeShop.addEventListener('change', recalcLabor);
        costModeExternal.addEventListener('change', recalcLabor);
        pricePerPiece.addEventListener('input', calculateIncidence);
        pricePerKg.addEventListener('input',    calculateIncidence);
        vatRate.addEventListener('change', () => {
            recalcLabor();
            calculateIncidence();
            recalcMargin();
        });
        totalPiecesIn.addEventListener('input', () => {
            updatePieceWeight();
            recalcMargin();
        });
        modePiece.addEventListener('change', updateMode);
        modeKg.addEventListener('change',    updateMode);
    
        document.getElementById('addIngredientBtn').addEventListener('click', () => {
            const first  = tableBody.querySelector('.ingredient-row');
            const clone  = first.cloneNode(true);
            const newIdx = idx++;
            clone.querySelectorAll('select[name], input[name]').forEach(el => {
                el.name = el.name.replace(/\[\d+\]/, `[${newIdx}]`);
                if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0;
                } else if (el.classList.contains('ingredient-quantity')) {
                    el.value = '0';
                } else {
                    // clear cost/incidence
                    el.value = '';
                }
            });
            tableBody.appendChild(clone);
            recalcRow(clone);
            recalcTotals();
        });
    
        tableBody.addEventListener('click', e => {
            if (e.target.closest('.remove-ingredient') && tableBody.children.length > 1) {
                e.target.closest('.ingredient-row').remove();
                recalcTotals();
            }
        });
    
        // ── initial pass ──
        document.querySelectorAll('.ingredient-row').forEach(r => recalcRow(r));
        updateMode();
        recalcTotals();
    });
    </script>
    
 


@endsection
