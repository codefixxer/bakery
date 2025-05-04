{{-- resources/views/frontend/external-supplies/create.blade.php --}}
@extends('frontend.layouts.app')

@section('title', isset($externalSupply) ? 'Edit External Supply' : 'Create External Supply')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">{{ isset($externalSupply) ? 'Edit' : 'Create' }} External Supply</h2>
  <form method="POST"
        action="{{ isset($externalSupply)
                   ? route('external-supplies.update', $externalSupply->id)
                   : route('external-supplies.store') }}"
        novalidate>
    @csrf
    @if(isset($externalSupply))
      @method('PUT')
    @endif

    {{-- Supply Name --}}
    <div class="mb-4">
      <label for="supply_name" class="form-label fw-semibold" id="supplyNameLabel">
        Supply Name
      </label>
      <input
        type="text"
        id="supply_name"
        name="supply_name"
        class="form-control"
        value="{{ old('supply_name', $externalSupply->supply_name ?? '') }}"
      >
      <div class="invalid-feedback">
        Please enter a template name when saving as template.
      </div>
    </div>

    {{-- Choose Template --}}
    @if(!isset($externalSupply))
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

    {{-- Supplier & Date --}}
    <div class="row mb-4 g-3 align-items-end">
      <div class="col-12 col-md-6">
        <label for="client_id" class="form-label fw-semibold">Client</label>
        <select id="client_id" name="client_id" class="form-select" required>
          <option value="">Select Client</option>
          @foreach($clients as $client)
            <option
              value="{{ $client->id }}"
              {{ old('client_id', $externalSupply->client_id ?? '') == $client->id ? 'selected' : '' }}
            >
              {{ $client->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-12 col-md-6">
        <label for="supply_date" class="form-label fw-semibold">Supply Date</label>
        <input
          type="date"
          id="supply_date"
          name="supply_date"
          class="form-control"
          value="{{ old(
            'supply_date',
            isset($externalSupply)
              ? $externalSupply->supply_date->format('Y-m-d')
              : ''
          ) }}"
          required
        >
      </div>
    </div>

    {{-- Save As --}}
    <div class="mb-4 col-12 col-md-4 px-0">
      <label for="template_action" class="form-label fw-semibold">Save As</label>
      <select id="template_action" name="template_action" class="form-select">
        @php
          $default = old(
            'template_action',
            isset($externalSupply)
              ? ($externalSupply->save_template ? 'template' : 'none')
              : 'none'
          );
        @endphp
        <option value="none"     {{ $default=='none'     ? 'selected' : '' }}>Just Save</option>
        <option value="template" {{ $default=='template' ? 'selected' : '' }}>Save as Template</option>
        <option value="both"     {{ $default=='both'     ? 'selected' : '' }}>Save &amp; Template</option>
      </select>
    </div>

    {{-- Supplied Products --}}
    <div class="card shadow-sm border-primary mb-4">
      <div class="card-header bg-primary text-white"><strong>Supplied Products</strong></div>
      <div class="card-body p-0">
        <style>
          #supplyTable { table-layout: fixed; width:100%; }
          #supplyTable .col-name   { width:40%; }
          #supplyTable .col-price  { width:150px; }
          #supplyTable .col-qty    { width:80px; }
          #supplyTable .col-total  { width:120px; }
          #supplyTable .col-action { width:60px; }
          #supplyTable th, #supplyTable td { padding:.5rem .75rem; vertical-align:middle; }
          #supplyTable select, #supplyTable input { width:100%; box-sizing:border-box; }
          #supplyTable .unit-field { white-space:nowrap; min-width:50px; }
        </style>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="supplyTable">
            <thead class="table-light">
              <tr>
                <th class="col-name">Recipe</th>
                <th class="col-price">Price (€)</th>
                <th class="col-qty">Qty Supplied</th>
                <th class="col-total">Total Amount (€)</th>
                <th class="col-action text-center">Action</th>
              </tr>
            </thead>
            <tbody id="supplyTableBody">
              @foreach(old('recipes', $externalSupply->recipes ?? [null]) as $index => $item)
                <tr class="supply-row">
                  <td>
                    <select name="recipes[{{ $index }}][id]"
                            class="form-select recipe-select"
                            required>
                      <option value="">Select Recipe</option>
                      @foreach($recipes as $rec)
                        <option value="{{ $rec->id }}"
                                data-price="{{ $rec->sell_mode==='kg'
                                              ? $rec->selling_price_per_kg
                                              : $rec->selling_price_per_piece }}"
                                data-sell-mode="{{ $rec->sell_mode }}"
                                {{ old("recipes.$index.id", $item->recipe_id ?? '') == $rec->id ? 'selected' : '' }}>
                          {{ $rec->recipe_name }}
                        </option>
                      @endforeach
                    </select>
                  </td>
                  <td class="col-price">
                    <div class="input-group input-group-sm flex-nowrap">
                      <span class="input-group-text">€</span>
                      <input type="text"
                             name="recipes[{{ $index }}][price]"
                             class="form-control text-end price-field"
                             readonly
                             placeholder="0.00"
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
          <button type="button" id="addRowBtn" class="btn btn-outline-success btn-sm">
            <i class="bi bi-plus-circle"></i> Add Recipe
          </button>
        </div>
      </div>
    </div>

    {{-- Totals --}}
    <div class="row mb-4 g-3">
      <div class="col-12 col-md-6">
        <label class="form-label fw-semibold">Total Amount (€)</label>
        <input type="text" id="totalAmount" name="total_amount" class="form-control"
               value="{{ old('total_amount', $externalSupply->total_amount ?? '') }}" readonly>
      </div>
    </div>

    {{-- Submit --}}
    <div class="text-end">
      <button type="submit" class="btn btn-lg btn-primary">
        <i class="bi bi-save2 me-2"></i>
        {{ isset($externalSupply) ? 'Update' : 'Save' }} External Supply
      </button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Ensure Supply Name is required when saving as template
  const actionSelect = document.getElementById('template_action');
  const nameInput    = document.getElementById('supply_name');
  const nameLabel    = document.getElementById('supplyNameLabel');

  function toggleNameRequirement() {
    const v = actionSelect.value;
    const isTemplate = v === 'template' || v === 'both';
    nameInput.required = isTemplate;
    nameLabel.textContent = isTemplate ? 'Template Name *' : 'Supply Name';
  }

  toggleNameRequirement();
  actionSelect.addEventListener('change', toggleNameRequirement);

  // Elements for product rows
  const nameLabelOld   = nameLabel; // placeholder to avoid collision
  const dateInput      = document.getElementById('supply_date');
  const templateSelect = document.getElementById('template_select');
  const supplyBody     = document.getElementById('supplyTableBody');
  const addBtn         = document.getElementById('addRowBtn');

  // 2) Grab a blank row for cloning
  let rowIndex = supplyBody.querySelectorAll('.supply-row').length;
  let blankRow;
  setTimeout(() => {
    blankRow = supplyBody.querySelector('.supply-row').cloneNode(true);
  }, 50);

  // 3) Add-row handler
  addBtn.addEventListener('click', () => {
    const clone = blankRow.cloneNode(true);
    clone.querySelectorAll('input, select').forEach(el => {
      el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
      if (el.tagName === 'SELECT') el.selectedIndex = 0;
      else if (el.type === 'number') el.value = '0';
      else el.value = '';
    });
    supplyBody.appendChild(clone);
    recalcRow(clone);
    rowIndex++;
  });

  // 4) Row recalc
  function recalcRow(row) {
    const opt      = row.querySelector('.recipe-select').selectedOptions[0];
    const priceIn  = row.querySelector('.price-field');
    const unitSpan = row.querySelector('.unit-field');
    const qtyIn    = row.querySelector('.qty-field');
    const totalIn  = row.querySelector('.total-field');

    const price = parseFloat(opt.dataset.price || 0).toFixed(2);
    const mode  = opt.dataset.sellMode || 'piece';
    priceIn.value        = price;
    unitSpan.textContent = mode === 'kg' ? '/kg' : '/piece';

    const qty = parseFloat(qtyIn.value || 0);
    totalIn.value = (price * qty).toFixed(2);
    calcSummary();
  }

  // 5) Summary calc
  function calcSummary() {
    let sum = 0;
    document.querySelectorAll('.total-field')
            .forEach(i => sum += parseFloat(i.value) || 0);
    document.getElementById('totalAmount').value = sum.toFixed(2);
  }

  // 6) Delegate recalc/remove on table
  supplyBody.addEventListener('change', e => {
    if (e.target.classList.contains('recipe-select'))
      recalcRow(e.target.closest('tr'));
  });
  supplyBody.addEventListener('input', e => {
    if (e.target.classList.contains('qty-field'))
      recalcRow(e.target.closest('tr'));
  });
  supplyBody.addEventListener('click', e => {
    if (e.target.closest('.remove-row') &&
        supplyBody.querySelectorAll('.supply-row').length > 1) {
      e.target.closest('tr').remove();
      calcSummary();
    }
  });

  // 7) Initialize existing rows
  supplyBody.querySelectorAll('.supply-row').forEach(r => recalcRow(r));

  // 8) Load template via AJAX
  templateSelect?.addEventListener('change', function() {
    const id = this.value;
    if (!id) return;

    fetch(`/external-supplies/template/${id}`)
      .then(res => res.json())
      .then(data => {
        // header fields
        document.getElementById('supply_name').value = data.supply_name;
        dateInput.value      = data.supply_date;
        actionSelect.value   = data.template_action;
        toggleNameRequirement();

        // rebuild table rows
        supplyBody.innerHTML = '';
        rowIndex = 0;

        data.rows.forEach(rowData => {
          const r = blankRow.cloneNode(true);
          r.querySelectorAll('input, select').forEach(el => {
            el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
          });
          r.querySelector('.recipe-select').value = rowData.recipe_id;
          r.querySelector('.qty-field').value      = rowData.qty;
          r.querySelector('.total-field').value    = parseFloat(rowData.total_amount).toFixed(2);

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
