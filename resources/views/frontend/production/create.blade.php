{{-- resources/views/frontend/production/create.blade.php --}}
@extends('frontend.layouts.app')

@section('title', isset($production) ? 'Edit Production' : 'Create Production Entry')

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-sm">
    <!-- Header with custom blue background and gold text -->
    <div class="card-header d-flex align-items-center" style="background-color: #041930; color: #e2ae76;">
      <i class="bi bi-calendar-plus fs-4 me-3"></i>
      <h5 class="mb-0" style="color: #e2ae76;">{{ isset($production) ? 'Edit' : 'Create' }} Production Entry</h5>
    </div>
    <div class="card-body">
      <form method="POST"
            action="{{ isset($production) ? route('production.update', $production->id) : route('production.store') }}"
            id="productionForm">
        @csrf
        @if(isset($production)) @method('PUT') @endif

        {{-- Production Name --}}
        <div class="mb-4">
          <label for="production_name" class="form-label fw-semibold" id="productionNameLabel">Production Name</label>
          <input type="text"
                 id="production_name"
                 name="production_name"
                 class="form-control"
                 value="{{ old('production_name', isset($production) ? $production->production_name : '') }}">
        </div>

        {{-- Template Selection --}}
        @if (!isset($production))
        <div class="mb-4">
          <label for="template_select" class="form-label fw-semibold">Choose Template</label>
          <select id="template_select" name="template_id" class="form-select">
            <option value="">-- Select Template --</option>
            @foreach($templates as $id => $name)
              <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
          </select>
        </div>
        @endif

        {{-- Production Date --}}
        <div class="mb-4">
          <label for="production_date" class="form-label fw-semibold">Production Date</label>
          <input type="date"
                 id="production_date"
                 name="production_date"
                 class="form-control"
                 value="{{ old('production_date', isset($production) ? $production->production_date : date('Y-m-d')) }}"
                 required>
        </div>

        {{-- Save As --}}
        <div class="mb-4 col-md-4 px-0">
          <label for="template_action" class="form-label fw-semibold">Save As</label>
          <select id="template_action" name="template_action" class="form-select">
            @php
              $default = old('template_action', isset($production) ? ($production->save_template ? 'template' : 'none') : 'none');
            @endphp
            <option value="none"     {{ $default=='none' ? 'selected' : '' }}>Just Save</option>
            <option value="template" {{ $default=='template' ? 'selected' : '' }}>Save as Template</option>
            <option value="both"     {{ $default=='both' ? 'selected' : '' }}>Save & Template</option>
          </select>
        </div>

        {{-- Details Table --}}
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
            @php $details = $production->details ?? collect([null]); @endphp
            @foreach($details as $index => $d)
            <tr>
              <td>
                <select name="recipe_id[]" class="form-select recipe-select" required>
                  <option value="">Select Recipe</option>
                  @foreach($recipes as $r)
                  <option value="{{ $r->id }}"
                          data-price-kg="{{ $r->selling_price_per_kg }}"
                          data-price-piece="{{ $r->selling_price_per_piece }}"
                          data-sell-mode="{{ $r->sell_mode }}"
                          {{ isset($d) && $r->id == $d->recipe_id ? 'selected' : '' }}>
                    {{ $r->recipe_name }}
                  </option>
                  @endforeach
                </select>
              </td>
              <td>
                <select name="pastry_chef_id[]" class="form-select" required>
                  <option value="">Select Chef</option>
                  @foreach($chefs as $c)
                    <option value="{{ $c->id }}" {{ isset($d) && $c->id == $d->pastry_chef_id ? 'selected' : '' }}>{{ $c->name }}</option>
                  @endforeach
                </select>
              </td>
              <td>
                <div class="input-group">
                  <input type="number"
                         name="quantity[]"
                         class="form-control quantity-input"
                         value="{{ $d->quantity ?? 1 }}"
                         min="1"
                         required>
                  <span class="input-group-text unit-label">/unit</span>
                </div>
              </td>
              <td>
                <input type="number"
                       name="execution_time[]"
                       class="form-control"
                       value="{{ $d->execution_time ?? '' }}"
                       min="1"
                       required>
              </td>
              <td>
                <div class="multi-equipment">
                  <div class="selected-equipment d-flex flex-wrap mb-2"></div>
                  <div class="input-group">
                    <select class="form-select equipment-select">
                      <option value="">Select Equipment</option>
                      @foreach($equipments as $e)
                        <option value="{{ $e->id }}">{{ $e->name }}</option>
                      @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-secondary add-equipment-btn">Add</button>
                  </div>
                  <input type="hidden" name="equipment_ids[{{ $loop->index }}][]" class="equipment-hidden" value="{{ $d->equipment_ids ?? '' }}">
                </div>
              </td>
              <td>
                <input type="text"
                       name="potential_revenue[]"
                       class="form-control revenue-field"
                       value="{{ $d->potential_revenue ?? '0.00' }}"
                       readonly>
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row">&times;</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

        <div class="mb-3">
          <!-- Add Row button with gold border and blue text -->
          <button type="button"
                  id="addRowBtn"
                  class="btn"
                  style="border: 1px solid #e2ae76; color: #041930; background-color: transparent;">
            + Add Row
          </button>
        </div>

        {{-- Total Revenue --}}
        <div class="mb-4">
          <label class="form-label fw-semibold">Total Potential Revenue (€)</label>
          <input type="text"
                 id="totalRevenue"
                 name="total_revenue"
                 class="form-control"
                 value="{{ old('total_revenue', isset($production) ? $production->total_potential_revenue : '0.00') }}"
                 readonly>
        </div>

        <div class="text-end">
          <!-- Save Production button with gold background and blue text -->
          <button type="submit"
                  class="btn btn-lg"
                  style="background-color: #e2ae76; color: #041930; border: none;">
            <i class="bi bi-save2 me-1"></i>
            {{ isset($production) ? 'Update' : 'Save' }} Production
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
  const templateSelect    = document.getElementById('template_select');
  const actionSelect      = document.getElementById('template_action');
  const nameLabel         = document.getElementById('productionNameLabel');
  const nameInput         = document.getElementById('production_name');

  function updateNameRequirement() {
    const v = actionSelect.value;
    nameLabel.textContent = (v === 'template' || v === 'both') ? 'Template Name' : 'Production Name';
    nameInput.required = (v === 'template' || v === 'both');
  }

  actionSelect.addEventListener('change', updateNameRequirement);
  updateNameRequirement();

  let blankRow;
  setTimeout(() => {
    blankRow = tableBody.rows[0].cloneNode(true);
  }, 50);

  function calculateRowRevenue(row) {
    const recipe     = row.querySelector('.recipe-select');
    const qtyInput   = row.querySelector('.quantity-input');
    const unitLabel  = row.querySelector('.unit-label');
    const priceKg    = +recipe.selectedOptions[0]?.dataset.priceKg || 0;
    const pricePiece = +recipe.selectedOptions[0]?.dataset.pricePiece || 0;
    const mode       = recipe.selectedOptions[0]?.dataset.sellMode || 'piece';
    const qty        = +qtyInput.value || 0;
    const price      = mode === 'kg' ? priceKg : pricePiece;

    unitLabel.textContent = '/' + mode;
    row.querySelector('.revenue-field').value = (qty * price).toFixed(2);
    calculateTotalRevenue();
  }

  function calculateTotalRevenue() {
    let sum = 0;
    document.querySelectorAll('.revenue-field').forEach(i => sum += +i.value || 0);
    totalRevenueField.value = sum.toFixed(2);
  }

  function attachRowEvents(row, idx) {
    const recipeSelect = row.querySelector('.recipe-select');
    const qtyInput     = row.querySelector('.quantity-input');
    const removeBtn    = row.querySelector('.remove-row');
    const equipmentSel = row.querySelector('.equipment-select');
    const equipmentBtn = row.querySelector('.add-equipment-btn');
    const selectedCont = row.querySelector('.selected-equipment');
    const hiddenInput  = row.querySelector('.equipment-hidden');

    function renderTags() {
      selectedCont.innerHTML = '';
      (hiddenInput.value ? hiddenInput.value.split(',') : []).forEach(id => {
        const opt = equipmentSel.querySelector(`option[value="${id}"]`);
        const label = opt ? opt.text : 'Unknown';
        const span = document.createElement('span');
        span.innerHTML = `${label} <span class="remove-tag" data-id="${id}">&times;</span>`;
        selectedCont.append(span);
      });
    }

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

    recipeSelect?.addEventListener('change', () => calculateRowRevenue(row));
    qtyInput?.addEventListener('input', () => calculateRowRevenue(row));
    removeBtn?.addEventListener('click', () => {
      if (tableBody.rows.length > 1) {
        row.remove();
        calculateTotalRevenue();
      }
    });
    equipmentSel?.addEventListener('change', addEquipment);
    equipmentBtn?.addEventListener('click', addEquipment);
    selectedCont?.addEventListener('click', e => {
      if (e.target.classList.contains('remove-tag')) {
        hiddenInput.value = hiddenInput.value
          .split(',')
          .filter(v => v !== e.target.dataset.id)
          .join(',');
        renderTags();
      }
    });

    renderTags();
    calculateRowRevenue(row);
  }

  setTimeout(() => {
    tableBody.querySelectorAll('tr').forEach((r, i) => attachRowEvents(r, i));
  }, 50);

  addRowBtn.addEventListener('click', () => {
    const idx = tableBody.rows.length;
    const clone = blankRow.cloneNode(true);
    clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    clone.querySelectorAll('input').forEach(i => {
      if (i.classList.contains('quantity-input')) i.value = 1;
      else if (i.classList.contains('revenue-field')) i.value = '0.00';
      else if (i.classList.contains('equipment-hidden')) {
        i.value = '';
        i.name = `equipment_ids[${idx}][]`;
      } else {
        i.value = '';
      }
    });
    clone.querySelector('.selected-equipment').innerHTML = '';
    tableBody.appendChild(clone);
    attachRowEvents(clone, idx);
  });

  templateSelect?.addEventListener('change', function () {
    const templateId = this.value;
    if (!templateId) {
      tableBody.innerHTML = '';
      const r = blankRow.cloneNode(true);
      r.querySelector('.equipment-hidden').name = 'equipment_ids[0][]';
      tableBody.appendChild(r);
      attachRowEvents(r, 0);
      totalRevenueField.value = '0.00';
      return;
    }

    fetch(`/production/template/${templateId}`)
      .then(res => res.json())
      .then(details => {
        tableBody.innerHTML = '';
        details.forEach((d, i) => {
          const r = blankRow.cloneNode(true);
          r.querySelector('.recipe-select').value = d.recipe_id;
          r.querySelector('select[name="pastry_chef_id[]"]').value = d.chef_id;
          r.querySelector('.quantity-input').value = d.quantity;
          r.querySelector('input[name="execution_time[]"]').value = d.execution_time;
          const hidden = r.querySelector('.equipment-hidden');
          hidden.name = `equipment_ids[${i}][]`;
          hidden.value = d.equipment_ids.join(',');
          tableBody.appendChild(r);
          attachRowEvents(r, i);
        });
        calculateTotalRevenue();
      })
      .catch(console.error);
  });
});
</script>
@endsection
