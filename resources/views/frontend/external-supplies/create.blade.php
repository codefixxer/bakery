{{-- resources/views/frontend/external-supply/create.blade.php --}}
@extends('frontend.layouts.app')

@section('title', isset($externalSupply) ? 'Edit External Supply' : 'Create External Supply')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">{{ isset($externalSupply) ? 'Edit' : 'Create' }} External Supply</h2>
  <form method="POST"
        action="{{ isset($externalSupply)
                   ? route('external-supplies.update', $externalSupply->id)
                   : route('external-supplies.store') }}">
    @csrf
    @if(isset($externalSupply))
      @method('PUT')
    @endif

    {{-- Supplier & Date --}}
    <div class="row mb-4 g-3 align-items-end">
      <div class="col-12 col-md-6">
        <label for="client_id" class="form-label fw-semibold">Client</label>
        <select id="client_id" name="client_id" class="form-select" required>
          <option value="">Select Client</option>
          @foreach($clients as $client)
            <option value="{{ $client->id }}"
              {{ old('client_id', $externalSupply->client_id ?? '') == $client->id ? 'selected' : '' }}>
              {{ $client->name }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-12 col-md-6">
        <label for="supply_date" class="form-label fw-semibold">Supply Date</label>
        <input type="date" id="supply_date" name="supply_date" class="form-control"
               value="{{ old('supply_date', $externalSupply->supply_date ?? '') }}" required>
      </div>
    </div>

    {{-- Supplied Products --}}
    <div class="card shadow-sm border-primary mb-4">
      <div class="card-header bg-primary text-white">
        <strong>Supplied Products</strong>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Recipe</th>
                <th>Price ($)</th>
                <th>Qty Supplied</th>
                <th>Total Amount ($)</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody id="supplyTable">
              @php $index = 0; @endphp
              @foreach(old('recipes', $externalSupply->recipes ?? [null]) as $index => $item)
                <tr class="supply-row">
                  {{-- Recipe --}}
                  <td>
                    <select name="recipes[{{ $index }}][id]"
                            class="form-select recipe-select"
                            required>
                      <option value="">Select Recipe</option>
                      @foreach($recipes as $rec)
                        <option value="{{ $rec->id }}"
                                data-price="{{ $rec->sell_mode === 'kg'
                                              ? $rec->selling_price_per_kg
                                              : $rec->selling_price_per_piece }}"
                                data-sell-mode="{{ $rec->sell_mode }}"
                                {{ old("recipes.$index.id", $item->recipe_id ?? '') == $rec->id ? 'selected' : '' }}>
                          {{ $rec->recipe_name }}
                        </option>
                      @endforeach
                    </select>
                  </td>
                  {{-- Price + Unit --}}
                  <td>
                    <div class="input-group">
                      <span class="input-group-text">$</span>
                      <input type="text"
                             name="recipes[{{ $index }}][price]"
                             class="form-control price-field"
                             readonly
                             value="{{ old("recipes.$index.price", $item->price ?? '') }}">
                      <span class="input-group-text unit-field"></span>
                    </div>
                  </td>
                  {{-- Qty --}}
                  <td>
                    <input type="number"
                           name="recipes[{{ $index }}][qty]"
                           class="form-control text-center qty-field"
                           required
                           value="{{ old("recipes.$index.qty", $item->qty ?? 0) }}">
                  </td>
                  {{-- Total Amount --}}
                  <td>
                    <input type="text"
                           name="recipes[{{ $index }}][total_amount]"
                           class="form-control total-field"
                           readonly
                           value="{{ old("recipes.$index.total_amount", $item->total_amount ?? '') }}">
                  </td>
                  {{-- Remove --}}
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
        <label class="form-label fw-semibold">Total Amount ($)</label>
        <input type="text" id="totalAmount" name="total_amount" class="form-control"
               value="{{ old('total_amount', $externalSupply->total_amount ?? '') }}" readonly>
      </div>
    </div>

    {{-- Submit Button --}}
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
  let rowIndex    = document.querySelectorAll('.supply-row').length;
  const supplyTable = document.getElementById('supplyTable');
  const addBtn      = document.getElementById('addRowBtn');

  // 1) Add new blank row
  addBtn.addEventListener('click', () => {
    const firstRow = supplyTable.querySelector('.supply-row');
    const newRow   = firstRow.cloneNode(true);

    newRow.querySelectorAll('input, select').forEach(el => {
      el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
      if (el.tagName === 'SELECT') {
        el.selectedIndex = 0;
      } else if (el.type === 'number') {
        el.value = '0';
      } else {
        el.value = '';
      }
    });

    supplyTable.appendChild(newRow);
    rowIndex++;
  });

  // 2) Recalculate a single row
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

    const qty    = parseFloat(qtyIn.value || 0);
    totalIn.value = (price * qty).toFixed(2);

    calcSummary();
  }

  // 3) Sum up totals
  function calcSummary() {
    let sum = 0;
    document.querySelectorAll('.total-field')
            .forEach(i => sum += parseFloat(i.value) || 0);
    document.getElementById('totalAmount').value = sum.toFixed(2);
  }

  // 4) Delegate for live updates
  supplyTable.addEventListener('change', e => {
    if (e.target.classList.contains('recipe-select')) {
      recalcRow(e.target.closest('tr'));
    }
  });
  supplyTable.addEventListener('input', e => {
    if (e.target.classList.contains('qty-field')) {
      recalcRow(e.target.closest('tr'));
    }
  });
  supplyTable.addEventListener('click', e => {
    if (e.target.closest('.remove-row') &&
        supplyTable.querySelectorAll('.supply-row').length > 1) {
      e.target.closest('tr').remove();
      calcSummary();
    }
  });

  // 5) Initialize existing rows on page load
  document.querySelectorAll('.supply-row').forEach(r => recalcRow(r));
});
</script>
@endsection
