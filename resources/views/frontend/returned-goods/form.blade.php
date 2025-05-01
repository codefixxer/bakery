{{-- resources/views/frontend/returned_goods/form.blade.php --}}
@extends('frontend.layouts.app')
@section('title','Create Returned Goods')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">Create Returned Goods</h2>

  <form method="POST" action="{{ route('returned-goods.store') }}">
    @csrf

    <input type="hidden" name="client_id" value="{{ $client->id }}">
    <input type="hidden" name="external_supply_id" value="{{ $external_supply_id }}">

    {{-- Return Date --}}
    <div class="mb-4">
      <label class="form-label" for="return_date">Return Date</label>
      <input type="date" id="return_date" name="return_date"
             class="form-control"
             value="{{ now()->format('Y-m-d') }}"
             readonly>
    </div>

    {{-- Returned Items --}}
    <div class="card mb-4">
      <div class="card-header bg-secondary text-white"><strong>Returned Items</strong></div>
      <div class="card-body p-0">
        <table id="returnedItemsTable" class="table mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>Recipe</th>
              <th>Qty Purchased</th>
              <th>Qty to Return</th>
              <th class="text-end">Unit Price (€)</th>
              <th class="text-end">Line Total (€)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recipes as $line)
              @php
                $unitPrice = $line->qty
                  ? round($line->total_amount / $line->qty, 2)
                  : 0;
              @endphp
              <tr data-unit-price="{{ $unitPrice }}">
                <td>{{ $line->recipe->recipe_name }}</td>
                <td>{{ $line->qty }}</td>
                <td>
                  <input type="number"
                         name="recipes[{{ $line->id }}][qty]"
                         class="form-control return-qty"
                         min="0" max="{{ $line->qty }}" value="0" required>
                  <input type="hidden"
                         name="recipes[{{ $line->id }}][price]"
                         value="{{ $unitPrice }}">
                </td>
                <td class="text-end">{{ number_format($unitPrice,2) }}</td>
                <td>
                  <input type="text"
                         name="recipes[{{ $line->id }}][total_amount]"
                         class="form-control line-total text-end"
                         value="0.00" readonly>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Grand Total --}}
    <div class="mb-4">
      <label class="form-label" for="total_amount">Total Amount (€)</label>
      <input type="text" id="total_amount" name="total_amount"
             class="form-control" value="0.00" readonly>
    </div>

    <button type="submit" class="btn btn-primary">
      <i class="bi bi-save2 me-1"></i> Save Return
    </button>
  </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const totalInput = document.getElementById('total_amount');
  const rows       = document.querySelectorAll('#returnedItemsTable tbody tr');

  function recalcAll() {
    let grand = 0;
    rows.forEach(row => {
      const qty   = parseFloat(row.querySelector('.return-qty').value) || 0;
      const price = parseFloat(row.dataset.unitPrice)             || 0;
      const line  = +(qty * price).toFixed(2);
      row.querySelector('.line-total').value = line.toFixed(2);
      grand += line;
    });
    totalInput.value = grand.toFixed(2);
  }

  rows.forEach(row => {
    row.querySelector('.return-qty').addEventListener('input', recalcAll);
  });

  recalcAll();
});
</script>
@endsection
