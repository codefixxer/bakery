{{-- resources/views/frontend/external-supplies/create.blade.php --}}
@extends('frontend.layouts.app')

@section('title', isset($externalSupply) ? 'Edit External Supply' : 'Create External Supply')

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-sm">
    <div class="card-header d-flex align-items-center gap-2"
         style="background-color: #041930; color: #e2ae76; padding: .5rem; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
      <iconify-icon
        icon="mdi:warehouse"
        class="me-2"
        style="font-size: 35px; color: #e2ae76;">
      </iconify-icon>
      <h5 class="mb-0" style="color: #e2ae76; font-size: 1.6vw;">
        {{ isset($externalSupply) ? 'Edit External Supply' : 'Create External Supply' }}
      </h5>
    </div>

    <div class="card-body">
      <form method="POST"
            action="{{ isset($externalSupply)
                       ? route('external-supplies.update', $externalSupply->id)
                       : route('external-supplies.store') }}"
            class="row g-3 needs-validation"
            novalidate>
        @csrf
        @if(isset($externalSupply))
          @method('PUT')
        @endif

        <!-- Supply Name -->
        <div class="col-md-6">
          <label id="supplyNameLabel" for="supply_name" class="form-label fw-semibold">
            Supply Name
          </label>
          <input
            type="text"
            id="supply_name"
            name="supply_name"
            class="form-control form-control-lg"
            required
            value="{{ old('supply_name', $externalSupply->supply_name ?? '') }}">
          <div class="invalid-feedback">Please enter a supply name.</div>
        </div>

        <!-- Client -->
        <div class="col-md-6">
          <label for="client_id" class="form-label fw-semibold">Client</label>
          <select id="client_id" name="client_id" class="form-select form-control-lg" required>
            <option value="">Select Client</option>
            @foreach($clients as $client)
              <option
                value="{{ $client->id }}"
                {{ old('client_id', $externalSupply->client_id ?? '') == $client->id ? 'selected' : '' }}>
                {{ $client->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Supply Date -->
        <div class="col-md-6">
          <label for="supply_date" class="form-label fw-semibold">Supply Date</label>
          <input
            type="date"
            id="supply_date"
            name="supply_date"
            class="form-control form-control-lg"
            required
            value="{{ old('supply_date', isset($externalSupply) ? $externalSupply->supply_date->format('Y-m-d') : '') }}">
        </div>

        <!-- Template Selection (only on create) -->
        @if(!isset($externalSupply))
        <div class="col-md-6">
          <label for="template_select" class="form-label fw-semibold">Choose Template</label>
          <select id="template_select" name="template_id" class="form-select form-control-lg">
            <option value="">-- Select Template --</option>
            @foreach($templates as $id => $name)
              <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
          </select>
        </div>
        @endif

        <!-- Save As -->
        <div class="col-md-6">
          <label for="template_action" class="form-label fw-semibold">Save As</label>
          @php
            $default = old('template_action',
              isset($externalSupply)
                ? ($externalSupply->save_template ? 'template' : 'none')
                : 'none');
          @endphp
          <select id="template_action" name="template_action" class="form-select form-control-lg">
            <option value="none"     {{ $default=='none'     ? 'selected' : '' }}>Just Save</option>
            <option value="template" {{ $default=='template' ? 'selected' : '' }}>Save as Template</option>
            <option value="both"     {{ $default=='both'     ? 'selected' : '' }}>Save & Template</option>
          </select>
        </div>

        <!-- Supplied Products -->
        <div class="col-12">
          <div class="card border-primary shadow-sm">
            <div class="card-header d-flex align-items-center" style="background-color: #041930;">
              <strong style="color: #e2ae76; font-size: 1.1rem;">Supplied Products</strong>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="supplyTable">
                  <thead class="table-light">
                    <tr>
                      <th style="width: 40%;">Recipe</th>
                      <th style="width: 150px;">Price (€)</th>
                      <th style="width: 80px;">Qty</th>
                      <th style="width: 120px;">Total (€)</th>
                      <th style="width: 60px;">Action</th>
                    </tr>
                  </thead>
                  <tbody id="supplyTableBody">
                    @foreach(old('recipes', $externalSupply->recipes ?? [null]) as $index => $item)
                      <tr class="supply-row">
                        <td>
                          <select name="recipes[{{ $index }}][id]" class="form-select recipe-select" required>
                            <option value="">Select Recipe</option>
                            @foreach($recipes as $rec)
                              <option value="{{ $rec->id }}"
                                      data-price="{{ $rec->sell_mode==='kg' ? $rec->selling_price_per_kg : $rec->selling_price_per_piece }}"
                                      data-sell-mode="{{ $rec->sell_mode }}"
                                      {{ old("recipes.$index.id", $item->recipe_id ?? '') == $rec->id ? 'selected' : '' }}>
                                {{ $rec->recipe_name }}
                              </option>
                            @endforeach
                          </select>
                        </td>
                        <td>
                          <div class="input-group input-group-sm">
                            <span class="input-group-text">€</span>
                            <input type="text"
                                   name="recipes[{{ $index }}][price]"
                                   class="form-control text-end price-field"
                                   readonly
                                   value="{{ old("recipes.$index.price", $item->price ?? '') }}">
                            <span class="input-group-text unit-field">/piece</span>
                          </div>
                        </td>
                        <td>
                          <input type="number"
                                 name="recipes[{{ $index }}][qty]"
                                 class="form-control text-center qty-field"
                                 required
                                 value="{{ old("recipes.$index.qty", $item->qty ?? 0) }}">
                        </td>
                        <td>
                          <input type="text"
                                 name="recipes[{{ $index }}][total_amount]"
                                 class="form-control total-field"
                                 readonly
                                 value="{{ old("recipes.$index.total_amount", $item->total_amount ?? '') }}">
                        </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                            <i class="bi bi-trash"></i>
                          </button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
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
        </div>

        <!-- Total Amount -->
        <div class="col-md-6">
          <label class="form-label fw-semibold">Total Amount (€)</label>
          <input type="text" id="totalAmount" name="total_amount" class="form-control" readonly
                 value="{{ old('total_amount', $externalSupply->total_amount ?? '') }}">
        </div>

        <!-- Submit Button -->
        <div class="col-12 text-end mt-4">
          <button type="submit" class="btn btn-lg" style="background-color: #e2ae76; color: #041930;">
            <i class="bi bi-save2 me-2" style="color: #041930;"></i>
            {{ isset($externalSupply) ? 'Update External Supply' : 'Save External Supply' }}
          </button>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const actionSelect      = document.getElementById('template_action');
  const nameInput         = document.getElementById('supply_name');
  const nameLabel         = document.getElementById('supplyNameLabel');
  const supplyBody        = document.getElementById('supplyTableBody');
  const addBtn            = document.getElementById('addRowBtn');
  const templateSelect    = document.getElementById('template_select');
  const totalAmountInput  = document.getElementById('totalAmount');
  const dateInput         = document.getElementById('supply_date');

  // Toggle label text & required attribute
  function toggleNameRequirement() {
    const v = actionSelect.value;
    const isTemplate = v === 'template' || v === 'both';
    nameLabel.textContent = isTemplate ? 'Template Name' : 'Supply Name';
    nameInput.required = isTemplate;
  }
  toggleNameRequirement();
  actionSelect.addEventListener('change', toggleNameRequirement);

  // Prepare blank row for adding
  let rowIndex = supplyBody.querySelectorAll('.supply-row').length;
  const baseRow = supplyBody.querySelector('.supply-row');
  function blankRow() {
    const clone = baseRow.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(el => {
      if (el.tagName === 'SELECT') el.selectedIndex = 0;
      else if (el.type === 'number') el.value = 0;
      else el.value = '';
    });
    return clone;
  }

  // Add new row
  addBtn.addEventListener('click', () => {
    const newRow = blankRow();
    newRow.querySelectorAll('input, select').forEach(el => {
      if (el.name) el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
    });
    supplyBody.appendChild(newRow);
    recalcRow(newRow);
    rowIndex++;
  });

  // Recalculate row total
  function recalcRow(row) {
    const opt      = row.querySelector('.recipe-select')?.selectedOptions[0];
    const priceIn  = row.querySelector('input[name*="[price]"]');
    const unitSpan = row.querySelector('.unit-field');
    const qtyIn    = row.querySelector('input[name*="[qty]"]');
    const totalIn  = row.querySelector('input[name*="[total_amount]"]');

    const price = parseFloat(opt?.dataset.price || 0).toFixed(2);
    const mode  = opt?.dataset.sellMode || 'piece';
    priceIn.value = price;
    unitSpan.textContent = mode === 'kg' ? '/kg' : '/piece';

    const qty = parseFloat(qtyIn.value || 0);
    totalIn.value = (price * qty).toFixed(2);
    calcSummary();
  }

  // Recalculate overall total
  function calcSummary() {
    let sum = 0;
    document.querySelectorAll('.total-field').forEach(input => {
      const val = parseFloat(input.value);
      if (!isNaN(val)) sum += val;
    });
    totalAmountInput.value = sum.toFixed(2);
  }

  // Attach live recalc to existing rows
  supplyBody.querySelectorAll('.supply-row').forEach(r => {
    r.querySelector('.recipe-select').addEventListener('change', () => recalcRow(r));
    r.querySelector('.qty-field').addEventListener('input', () => recalcRow(r));
    r.querySelector('.remove-row').addEventListener('click', () => {
      if (supplyBody.querySelectorAll('.supply-row').length > 1) {
        r.remove();
        calcSummary();
      }
    });
  });

  // Load template details
  templateSelect?.addEventListener('change', function() {
    const id = this.value;
    if (!id) return;
    fetch(`/external-supplies/template/${id}`)
      .then(res => res.json())
      .then(data => {
        document.getElementById('supply_name').value = data.supply_name;
        dateInput.value = data.supply_date;
        actionSelect.value = data.template_action;
        toggleNameRequirement();

        supplyBody.innerHTML = '';
        rowIndex = 0;
        data.rows.forEach(rowData => {
          const r = blankRow();
          r.querySelectorAll('input, select').forEach(el => {
            if (el.name) el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
          });
          r.querySelector('.recipe-select').value = rowData.recipe_id;
          r.querySelector('input[name*="[qty]"]').value = rowData.qty;
          r.querySelector('input[name*="[total_amount]"]').value = parseFloat(rowData.total_amount).toFixed(2);
          supplyBody.appendChild(r);
          recalcRow(r);
          rowIndex++;
        });
      })
      .catch(console.error);
  });
});
</script>
@endsection
