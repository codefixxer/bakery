@extends('frontend.layouts.app')

@section('title','Return Goods')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Header -->
  <div class="card border-primary shadow-sm mb-4">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-arrow-counterclockwise me-2 fs-4" style="color: #e2ae76;"></i>
      <h5 class="mb-0" style="color: #e2ae76;">
        Return for {{ $externalSupply->client->name }} — {{ $externalSupply->supply_name }}
        <small class="d-block text-muted" style="font-size: 0.8rem;">Supply Date: {{ $externalSupply->supply_date->format('Y-m-d') }}</small>
      </h5>
    </div>

    <div class="card-body">
      <form method="POST" action="{{ route('returned-goods.store') }}">
        @csrf
        <input type="hidden" name="client_id" value="{{ $externalSupply->client->id }}">
        <input type="hidden" name="external_supply_id" value="{{ $externalSupply->id }}">

        <!-- Return Date -->
        <div class="row mb-4">
          <div class="col-md-4">
            <label for="return_date" class="form-label fw-semibold">Return Date</label>
            <input
              type="date"
              id="return_date"
              name="return_date"
              class="form-control form-control-lg"
              value="{{ old('return_date', now()->format('Y-m-d')) }}"
              required>
          </div>
        </div>

        <!-- Return Table -->
        <div class="card-body px-4">
          <div class="table-responsive p-3">
            <table
              id="returnTable"
              class="table table-bordered table-striped table-hover align-middle mb-0 text-center"
              data-page-length="10"
            >
              <thead>
                <tr class="text-center">
                  <th class="text-center">Recipe</th>
                  <th class="text-center">Original Qty</th>
                  <th class="text-center">Returned So Far</th>
                  <th class="text-center">Remaining</th>
                  <th class="text-center">Qty to Return</th>
                  <th class="text-center">Unit Price (€)</th>
                  <th class="text-center">Line Total (€)</th>
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
                    <td>
                      <input 
                        type="number"
                        name="recipes[{{ $line->id }}][qty]"
                        class="form-control form-control-sm return-qty text-center"
                        min="0"
                        max="{{ $remaining }}"
                        value="{{ old("recipes.{$line->id}.qty", 0) }}">
                    </td>
                    <td>€{{ number_format($line->price, 2) }}</td>
                    <td>
                      <input 
                        type="text"
                        name="recipes[{{ $line->id }}][total_amount]"
                        class="form-control form-control-sm total-return text-end"
                        value="0.00"
                        readonly>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        

        <!-- Submit Button -->
        <div class="text-end">
          <button class="btn btn-lg fw-semibold" style="background-color: #e2ae76; color: #041930;">
            <i class="bi bi-arrow-counterclockwise me-2" style="color: #041930;"></i>
            Submit Return
          </button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<style>
  /* Match table heading with ingredients style */
  table#returnTable thead th {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    text-align: center;
    vertical-align: middle;
    font-weight: 600;
  }

  table#returnTable td {
    text-align: center;
    vertical-align: middle;
  }

  /* DataTables sorting arrow override */
  table.dataTable thead .sorting:after,
  table.dataTable thead .sorting_asc:after,
  table.dataTable thead .sorting_desc:after {
    color: #041930 !important;
  }

  .dataTables_length select {
    appearance: none;
    background: #fff url('data:image/svg+xml;utf8,<svg fill="%23041930" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 10px center;
    padding-right: 30px;
    border: 1px solid #e2ae76;
    color: #041930;
    font-weight: 500;
    border-radius: 4px;
  }

  .dataTables_wrapper .dataTables_filter input {
    border-radius: 5px;
    padding: 6px 10px;
    border: 1px solid #e2ae76;
  }

  /* Optional: pagination or info text */
  .dataTables_wrapper .dataTables_info,
  .dataTables_wrapper .dataTables_paginate {
    color: #041930;
    font-weight: 500;
  }
</style>


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Line Total Calculation
    document.querySelectorAll('.return-qty').forEach(input => {
      input.addEventListener('input', function () {
        const tr = this.closest('tr');
        const qty = parseFloat(this.value) || 0;
        const priceText = tr.querySelector('td:nth-child(6)').textContent;
        const price = parseFloat(priceText.replace(/[^0-9.]/g, '')) || 0;
        tr.querySelector('.total-return').value = (price * qty).toFixed(2);
      });
    });

    // DataTables init
    $('#returnTable').DataTable({
      paging: true,
      searching: true,
      ordering: true,
      order: [[0, 'asc']],
      columnDefs: [
        { className: "text-center", targets: "_all" }
      ]
    });
  });
</script>
@endsection
