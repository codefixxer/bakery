@extends('frontend.layouts.app')

@section('title', isset($production) ? 'Edit Production' : 'Create Production Entry')

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-calendar-plus fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($production) ? 'Edit' : 'Create' }} Production Entry</h5>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ isset($production) ? route('production.update', $production->id) : route('production.store') }}" id="productionForm">
        @csrf
        @if(isset($production))
          @method('PUT')
        @endif

        <!-- Production Date -->
        <div class="mb-4">
          <label for="production_date" class="form-label fw-semibold">Production Date</label>
          <input type="date" id="production_date" name="production_date" class="form-control" 
                 value="{{ old('production_date', isset($production) ? $production->production_date : date('Y-m-d')) }}" required>
        </div>

        <!-- Table -->
        <table class="table table-bordered align-middle" id="recipeTable">
          <thead class="table-light">
            <tr>
              <th>Recipe</th>
              <th>Chef</th>
              <th>Qty</th>
              <th>Time (min)</th>
              <th>Equipment</th>
              <th>Revenue (€)</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @if(isset($production))
              @foreach($production->details as $index => $detail)
              <tr>
                <td>
                  <select name="recipe_id[]" class="form-select recipe-select" required>
                    <option value="">Select Recipe</option>
                    @foreach($recipes as $recipe)
                      <option value="{{ $recipe->id }}"
                              data-price-kg="{{ $recipe->selling_price_per_kg }}"
                              data-price-piece="{{ $recipe->selling_price_per_piece }}"
                              {{ $recipe->id == $detail->recipe_id ? 'selected' : '' }}>
                        {{ $recipe->recipe_name }}
                      </option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <select name="pastry_chef_id[]" class="form-select" required>
                    <option value="">Select Chef</option>
                    @foreach($chefs as $chef)
                      <option value="{{ $chef->id }}" {{ $chef->id == $detail->pastry_chef_id ? 'selected' : '' }}>
                        {{ $chef->name }}
                      </option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <input type="number" name="quantity[]" class="form-control quantity-input"
                         value="{{ $detail->quantity }}" min="1" required>
                </td>
                <td>
                  <input type="number" name="execution_time[]" class="form-control"
                         value="{{ $detail->execution_time }}" min="1" required>
                </td>
                <td>
                  <div class="multi-equipment">
                    <div class="selected-equipment d-flex flex-wrap mb-2"></div>
                    <div class="input-group">
                      <select class="form-select equipment-select">
                        <option value="">Select Equipment</option>
                        @foreach($equipments as $equipment)
                          <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                        @endforeach
                      </select>
                      <button type="button" class="btn btn-outline-secondary add-equipment-btn">Add</button>
                    </div>
                    <input type="hidden" name="equipment_ids[{{ $index }}][]" 
                           class="equipment-hidden" 
                           value="{{ is_array($detail->equipment_ids) ? implode(',', $detail->equipment_ids) : $detail->equipment_ids }}">
                  </div>
                </td>
                <td>
                  <input type="text" name="potential_revenue[]" 
                         class="form-control revenue-field" 
                         value="{{ $detail->potential_revenue }}" readonly>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-danger btn-sm remove-row">&times;</button>
                </td>
              </tr>
              @endforeach
            @else
              <tr>
                <td>
                  <select name="recipe_id[]" class="form-select recipe-select" required>
                    <option value="" data-price-kg="0" data-price-piece="0">Select Recipe</option>
                    @foreach($recipes as $recipe)
                      <option value="{{ $recipe->id }}"
                              data-price-kg="{{ $recipe->selling_price_per_kg }}"
                              data-price-piece="{{ $recipe->selling_price_per_piece }}">
                        {{ $recipe->recipe_name }}
                      </option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <select name="pastry_chef_id[]" class="form-select" required>
                    <option value="">Select Chef</option>
                    @foreach($chefs as $chef)
                      <option value="{{ $chef->id }}">{{ $chef->name }}</option>
                    @endforeach
                  </select>
                </td>
                <td>
                  <input type="number" name="quantity[]" class="form-control quantity-input" value="1" min="1" required>
                </td>
                <td>
                  <input type="number" name="execution_time[]" class="form-control" min="1" required>
                </td>
                <td>
                  <div class="multi-equipment">
                    <div class="selected-equipment d-flex flex-wrap mb-2"></div>
                    <div class="input-group">
                      <select class="form-select equipment-select">
                        <option value="">Select Equipment</option>
                        @foreach($equipments as $equipment)
                          <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                        @endforeach
                      </select>
                      <button type="button" class="btn btn-outline-secondary add-equipment-btn">Add</button>
                    </div>
                    <input type="hidden" name="equipment_ids[0][]" class="equipment-hidden">
                  </div>
                </td>
                <td>
                  <input type="text" name="potential_revenue[]" class="form-control revenue-field" value="0.00" readonly>
                </td>
                <td class="text-center">
                  <button type="button" class="btn btn-danger btn-sm remove-row">&times;</button>
                </td>
              </tr>
            @endif
          </tbody>
        </table>

        <div class="mb-3">
          <button type="button" class="btn btn-outline-primary" id="addRowBtn">+ Add Row</button>
        </div>

        <!-- Total Revenue -->
        <div class="mb-4">
          <label class="form-label fw-semibold">Total Potential Revenue (€)</label>
          <input type="text" id="totalRevenue" name="total_revenue" class="form-control"
                 value="{{ old('total_revenue', isset($production) ? $production->total_potential_revenue : '0.00') }}" readonly>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-save2 me-1"></i> {{ isset($production) ? 'Update' : 'Save' }} Production
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.selected-equipment span {
  background: #e9ecef;
  border-radius: 15px;
  padding: 4px 10px;
  margin-right: 5px;
  margin-bottom: 5px;
  font-size: 0.875rem;
  position: relative;
}
.selected-equipment span .remove-tag {
  color: #888;
  margin-left: 6px;
  cursor: pointer;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const tableBody = document.querySelector('#recipeTable tbody');
  const addRowBtn = document.getElementById('addRowBtn');
  const totalRevenueField = document.getElementById('totalRevenue');

  function calculateRowRevenue(row) {
    const recipe = row.querySelector('.recipe-select');
    const qty = parseFloat(row.querySelector('.quantity-input')?.value || 0);
    const priceKg = parseFloat(recipe.selectedOptions[0]?.dataset.priceKg || 0);
    const pricePiece = parseFloat(recipe.selectedOptions[0]?.dataset.pricePiece || 0);
    const price = priceKg > 0 ? priceKg : pricePiece;
    const revenue = (qty * price).toFixed(2);
    row.querySelector('.revenue-field').value = revenue;
    calculateTotalRevenue();
  }

  function calculateTotalRevenue() {
    let total = 0;
    document.querySelectorAll('.revenue-field').forEach(input => {
      total += parseFloat(input.value) || 0;
    });
    totalRevenueField.value = total.toFixed(2);
  }

  function attachRowEvents(row) {
    const recipe = row.querySelector('.recipe-select');
    const qty = row.querySelector('.quantity-input');
    const removeBtn = row.querySelector('.remove-row');
    const equipmentSelect = row.querySelector('.equipment-select');
    const addBtn = row.querySelector('.add-equipment-btn');
    const selectedContainer = row.querySelector('.selected-equipment');
    const hiddenInput = row.querySelector('.equipment-hidden');

    // Attach events
    if (recipe) recipe.addEventListener('change', () => calculateRowRevenue(row));
    if (qty) qty.addEventListener('input', () => calculateRowRevenue(row));

    if (removeBtn) {
      removeBtn.addEventListener('click', () => {
        if (tableBody.rows.length > 1) {
          row.remove();
          calculateTotalRevenue();
        }
      });
    }

    if (addBtn && equipmentSelect && hiddenInput) {
      addBtn.addEventListener('click', () => {
        const id = equipmentSelect.value;
        const label = equipmentSelect.options[equipmentSelect.selectedIndex]?.text;
        if (!id) return;
        let current = hiddenInput.value ? hiddenInput.value.split(',') : [];
        if (!current.includes(id)) {
          current.push(id);
          hiddenInput.value = current.join(',');
          updateEquipmentTags();
        }
        equipmentSelect.selectedIndex = 0;
      });
    }

    if (selectedContainer) {
      selectedContainer.addEventListener('click', e => {
        if (e.target.classList.contains('remove-tag')) {
          const id = e.target.dataset.id;
          let current = hiddenInput.value.split(',').filter(val => val !== id);
          hiddenInput.value = current.join(',');
          updateEquipmentTags();
        }
      });
    }

    function updateEquipmentTags() {
      selectedContainer.innerHTML = '';
      const ids = hiddenInput.value ? hiddenInput.value.split(',') : [];
      ids.forEach(id => {
        const label = equipmentSelect.querySelector(`option[value="${id}"]`)?.text || 'Unknown';
        const tag = document.createElement('span');
        tag.innerHTML = `${label} <span class="remove-tag" data-id="${id}">&times;</span>`;
        selectedContainer.appendChild(tag);
      });
    }

    updateEquipmentTags(); // show equipment tags initially
    calculateRowRevenue(row); // force revenue calc on edit
  }

  // ✅ FOR EDIT MODE — Attach to existing rows after DOM ready
  setTimeout(() => {
    document.querySelectorAll('#recipeTable tbody tr').forEach(row => {
      attachRowEvents(row);
      calculateRowRevenue(row);
    });
  }, 100); // slight delay to ensure DOM loaded

  // ✅ For dynamically added row
  addRowBtn.addEventListener('click', () => {
    const newRow = tableBody.rows[0].cloneNode(true);
    const index = tableBody.rows.length;

    newRow.querySelectorAll('input, select').forEach(el => {
      if (el.tagName === 'SELECT') el.selectedIndex = 0;
      else el.value = el.classList.contains('quantity-input') ? 1 : '';
    });

    newRow.querySelector('.revenue-field').value = '0.00';
    newRow.querySelector('.equipment-hidden').value = '';
    newRow.querySelector('.equipment-hidden').setAttribute('name', `equipment_ids[${index}][]`);
    newRow.querySelector('.selected-equipment').innerHTML = '';

    tableBody.appendChild(newRow);
    attachRowEvents(newRow);
  });
});
</script>
@endsection

