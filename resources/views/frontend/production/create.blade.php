{{-- resources/views/frontend/production/form.blade.php --}}
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
      <form method="POST"
            action="{{ isset($production) ? route('production.update', $production->id) : route('production.store') }}"
            id="productionForm">
        @csrf
        @if(isset($production)) @method('PUT') @endif

        {{-- Production Date --}}
        <div class="mb-4">
          <label for="production_date" class="form-label fw-semibold">Production Date</label>
          <input type="date" id="production_date" name="production_date" class="form-control"
                 value="{{ old('production_date', isset($production) ? $production->production_date : date('Y-m-d')) }}"
                 required>
        </div>

        {{-- Table --}}
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
                  {{-- Recipe --}}
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

                  {{-- Chef --}}
                  <td>
                    <select name="pastry_chef_id[]" class="form-select" required>
                      <option value="">Select Chef</option>
                      @foreach($chefs as $chef)
                        <option value="{{ $chef->id }}"
                                {{ $chef->id == $detail->pastry_chef_id ? 'selected' : '' }}>
                          {{ $chef->name }}
                        </option>
                      @endforeach
                    </select>
                  </td>

                  {{-- Quantity --}}
                  <td>
                    <input type="number" name="quantity[]" class="form-control quantity-input"
                           value="{{ $detail->quantity }}" min="1" required>
                  </td>

                  {{-- Time --}}
                  <td>
                    <input type="number" name="execution_time[]" class="form-control"
                           value="{{ $detail->execution_time }}" min="1" required>
                  </td>

                  {{-- Equipment --}}
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
                      <input type="hidden"
                             name="equipment_ids[{{ $index }}][]"
                             class="equipment-hidden"
                             value="{{ is_array($detail->equipment_ids) ? implode(',', $detail->equipment_ids) : $detail->equipment_ids }}">
                    </div>
                  </td>

                  {{-- Revenue --}}
                  <td>
                    <input type="text" name="potential_revenue[]"
                           class="form-control revenue-field"
                           value="{{ $detail->potential_revenue }}" readonly>
                  </td>

                  {{-- Remove Row --}}
                  <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row">&times;</button>
                  </td>
                </tr>
              @endforeach
            @else
              {{-- empty state: one blank row --}}
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
                  <input type="number" name="quantity[]" class="form-control quantity-input"
                         value="1" min="1" required>
                </td>
                <td>
                  <input type="number" name="execution_time[]" class="form-control"
                         min="1" required>
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
                      {{-- <button type="button" class="btn btn-outline-secondary add-equipment-btn">Add</button> --}}
                    </div>
                    <input type="hidden" name="equipment_ids[0][]" class="equipment-hidden">
                  </div>
                </td>
                <td>
                  <input type="text" name="potential_revenue[]" class="form-control revenue-field"
                         value="0.00" readonly>
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

        {{-- Total Revenue --}}
        <div class="mb-4">
          <label class="form-label fw-semibold">Total Potential Revenue (€)</label>
          <input type="text" id="totalRevenue" name="total_revenue" class="form-control"
                 value="{{ old('total_revenue', isset($production) ? $production->total_potential_revenue : '0.00') }}"
                 readonly>
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
}
.selected-equipment .remove-tag {
  color: #888;
  margin-left: 6px;
  cursor: pointer;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const tableBody         = document.querySelector('#recipeTable tbody');
  const addRowBtn         = document.getElementById('addRowBtn');
  const totalRevenueField = document.getElementById('totalRevenue');

  // Recalc revenue for one row
  function calculateRowRevenue(row) {
    const recipe  = row.querySelector('.recipe-select');
    const qty     = +row.querySelector('.quantity-input').value || 0;
    const priceKg    = +recipe.selectedOptions[0].dataset.priceKg  || 0;
    const pricePiece = +recipe.selectedOptions[0].dataset.pricePiece|| 0;
    const price      = priceKg > 0 ? priceKg : pricePiece;
    row.querySelector('.revenue-field').value = (qty * price).toFixed(2);
    calculateTotalRevenue();
  }

  // Sum all row revenues
  function calculateTotalRevenue() {
    let sum = 0;
    document.querySelectorAll('.revenue-field').forEach(i => sum += +i.value || 0);
    totalRevenueField.value = sum.toFixed(2);
  }

  // Wire up one row
  function attachRowEvents(row, idx) {
    const recipeSelect   = row.querySelector('.recipe-select');
    const qtyInput       = row.querySelector('.quantity-input');
    const removeBtn      = row.querySelector('.remove-row');
    const equipmentSel   = row.querySelector('.equipment-select');
    const equipmentBtn   = row.querySelector('.add-equipment-btn');
    const selectedCont   = row.querySelector('.selected-equipment');
    const hiddenInput    = row.querySelector('.equipment-hidden');

    // unified add logic
    function addEquipment() {
      const id = equipmentSel.value;
      if (!id) return;
      const list = hiddenInput.value ? hiddenInput.value.split(',') : [];
      if (!list.includes(id)) {
        list.push(id);
        hiddenInput.value = list.join(',');
        renderTags();
      }
      equipmentSel.selectedIndex = 0;
    }

    // render tags from hiddenInput
    function renderTags() {
      selectedCont.innerHTML = '';
      (hiddenInput.value ? hiddenInput.value.split(',') : []).forEach(id => {
        const opt   = equipmentSel.querySelector(`option[value="${id}"]`);
        const label = opt ? opt.text : 'Unknown';
        const span  = document.createElement('span');
        span.innerHTML = `${label} <span class="remove-tag" data-id="${id}">&times;</span>`;
        selectedCont.append(span);
      });
    }

    // events
    recipeSelect.addEventListener('change', () => calculateRowRevenue(row));
    qtyInput.addEventListener('input',   () => calculateRowRevenue(row));
    removeBtn.addEventListener('click',  () => {
      if (tableBody.rows.length > 1) { row.remove(); calculateTotalRevenue(); }
    });

    // both select-change and button click add equipment
    equipmentSel.addEventListener('change', addEquipment);
    equipmentBtn.addEventListener('click',  addEquipment);

    // remove a tag
    selectedCont.addEventListener('click', e => {
      if (e.target.classList.contains('remove-tag')) {
        const keep = hiddenInput.value.split(',').filter(v => v!== e.target.dataset.id);
        hiddenInput.value = keep.join(',');
        renderTags();
      }
    });

    // init
    renderTags();
    calculateRowRevenue(row);
  }

  // attach to existing
  setTimeout(() => {
    tableBody.querySelectorAll('tr').forEach((r,i) => attachRowEvents(r, i));
  }, 50);

  // Add new row
  addRowBtn.addEventListener('click', () => {
    const clone = tableBody.rows[0].cloneNode(true);
    const idx   = tableBody.rows.length;
    // reset selects/inputs
    clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    clone.querySelectorAll('input').forEach(i => {
      if (i.classList.contains('quantity-input')) i.value = 1;
      else if (i.classList.contains('revenue-field')) i.value = '0.00';
      else if (i.classList.contains('equipment-hidden')) {
        i.value = ''; i.name = `equipment_ids[${idx}][]`;
      } else i.value = '';
    });
    clone.querySelector('.selected-equipment').innerHTML = '';
    tableBody.appendChild(clone);
    attachRowEvents(clone, idx);
  });
});
</script>
@endsection
