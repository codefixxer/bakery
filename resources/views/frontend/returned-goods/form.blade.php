{{-- resources/views/frontend/returned-goods/form.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Return Goods')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">
    Return for {{ $externalSupply->client->name }}
    &mdash; {{ $externalSupply->supply_name }}
    <small class="text-muted">{{ $externalSupply->supply_date->toDateString() }}</small>
  </h2>

  <form method="POST" action="{{ route('returned-goods.store') }}">
    @csrf

    {{-- Hidden IDs --}}
    <input type="hidden" name="client_id" value="{{ $externalSupply->client->id }}">
    <input type="hidden" name="external_supply_id" value="{{ $externalSupply->id }}">

    {{-- Return Date --}}
    <div class="mb-4">
      <label for="return_date" class="form-label">Return Date</label>
      <input
        type="date"
        id="return_date"
        name="return_date"
        class="form-control"
        value="{{ old('return_date', now()->format('Y-m-d')) }}"
        required
      >
    </div>

    {{-- Return‐Qty Table --}}
    <div class="table-responsive mb-4">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Recipe</th>
            <th>Original Qty</th>
            <th>Returned So Far</th>
            <th>Remaining</th>
            <th class="text-center">Qty to Return</th>
            <th class="text-end">Unit Price</th>
            <th class="text-end">Line Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($externalSupply->recipes as $line)
            @php
              $returnedQty = $line->returns->sum('qty');
              $remaining = $line->qty - $returnedQty;
            @endphp
            <tr>
              <td>{{ $line->recipe->recipe_name }}</td>
              <td>{{ $line->qty }}</td>
              <td>{{ $returnedQty }}</td>
              <td>{{ $remaining }}</td>
              <td class="text-center">
                <input 
                  type="number"
                  name="recipes[{{ $line->id }}][qty]"
                  class="form-control return-qty text-center"
                  min="0"
                  max="{{ $remaining }}"
                  value="{{ old("recipes.{$line->id}.qty", 0) }}"
                >
              </td>
              <td class="text-end">€ {{ number_format($line->price, 2) }}</td>
              <td class="text-end">
                <input
                  type="text"
                  name="recipes[{{ $line->id }}][total_amount]"
                  class="form-control total-return text-end"
                  value="0.00"
                  readonly
                >
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="text-end">
      <button class="btn btn-primary">
        <i class="bi bi-arrow-counterclockwise me-1"></i>
        Submit Return
      </button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.return-qty').forEach(input => {
    input.addEventListener('input', function() {
      const tr = this.closest('tr');
      const qty = parseInt(this.value) || 0;
      const priceText = tr.querySelector('td:nth-child(6)').textContent;
      const price = parseFloat(priceText.replace(/[^0-9.]/g, '')) || 0;
      tr.querySelector('.total-return').value = (price * qty).toFixed(2);
    });
  });
});
</script>
@endsection
