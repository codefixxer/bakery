@extends('frontend.layouts.app')

@section('title', 'Returned Goods')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">Returned Goods</h2>

  {{-- FILTERS --}}
{{-- FILTERS --}}
<form method="GET" class="row g-3 mb-4">
  <div class="col-md-3">
    <label class="form-label">Client</label>
    <select name="client_id" class="form-select">
      <option value="">All clients</option>
      @foreach($clients as $c)
        <option value="{{ $c->id }}"
          {{ request('client_id') == $c->id ? 'selected' : '' }}>
          {{ $c->name }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">From</label>
    <input type="date" name="start_date" class="form-control"
           value="{{ request('start_date') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">To</label>
    <input type="date" name="end_date" class="form-control"
           value="{{ request('end_date') }}">
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <button type="submit" class="btn btn-primary w-100">
      <i class="bi bi-funnel"></i> Apply Filters
    </button>
  </div>
</form>

{{-- SUMMARY CARDS --}}
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card text-white bg-success h-100">
      <div class="card-body d-flex align-items-center">
        <i class="bi bi-box-seam fs-2 me-3"></i>
        <div>
          <div class="fs-5">Total Supplies</div>
          <div class="fs-4">€{{ number_format($grandSupply, 2) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-white bg-danger h-100">
      <div class="card-body d-flex align-items-center">
        <i class="bi bi-arrow-counterclockwise fs-2 me-3"></i>
        <div>
          <div class="fs-5">Total Returns</div>
          <div class="fs-4">€{{ number_format($grandReturn, 2) }}</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card text-white {{ $grandNet >= 0 ? 'bg-primary' : 'bg-warning' }} h-100">
      <div class="card-body d-flex align-items-center">
        <i class="bi bi-cash-stack fs-2 me-3"></i>
        <div>
          <div class="fs-5">Net Income</div>
          <div class="fs-4">€{{ number_format($grandNet, 2) }}</div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- TWO-COLUMN LAYOUT --}}
<div class="row g-4">
  {{-- A) All Supplies (formerly "All Returns") --}}
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-secondary text-white">
        All Supplies
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Client</th>
                <th>External Supplies (€)</th> <!-- External supplies column -->
                <th>Return (€)</th> <!-- Return amount column -->
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($returnedGoods as $r)
                <tr>
                  <td>{{ $r->return_date->format('Y-m-d') }}</td>
                  <td>{{ $r->client->name }}</td>
                  <td>€{{ number_format($r->externalSupply->total_amount ?? 0, 2) }}</td> <!-- Showing external supplies -->
                  <td>€{{ number_format($r->total_amount, 2) }}</td> <!-- Showing return amount -->
                  <td class="text-end">
                    <a href="{{ route('returned-goods.edit', $r->id) }}"
                       class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('returned-goods.destroy', $r->id) }}"
                          method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this return?');">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted">
                    No supplies found.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="card-footer">
          {{ $returnedGoods->links() }}
        </div>
      </div>
    </div>
  </div>

  {{-- B) Daily Comparison Table --}}
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-primary text-white">
        Daily Comparison (Sold - Returned)
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Supply (€)</th>
                <th>Returned (€)</th>
                <th>Net (€)</th>
              </tr>
            </thead>
            <tbody>
              @forelse($report as $row)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($row->date)->format('Y-m-d') }}</td>
                  <td>€{{ number_format($row->total_supply, 2) }}</td>
                  <td class="{{ $row->total_return > 0 ? 'text-danger' : 'text-success' }}">
                    €{{ number_format($row->total_return, 2) }}
                  </td>
                  <td class="{{ $row->net >= 0 ? 'text-success' : 'text-danger' }}">
                    €{{ number_format($row->net, 2) }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">
                    No data to compare.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Add filter and sort functionalities dynamically without page reloads
  const clientSelect = document.getElementById('filterDept');
  const categorySelect = document.getElementById('filterCategory');
  const modeSelect = document.getElementById('filterMode');
  const startDate = document.getElementById('start_date');
  const endDate = document.getElementById('end_date');

  const updateFiltersAndSort = () => {
    // Construct query string based on selected filters
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('client_id', clientSelect.value);
    urlParams.set('start_date', startDate.value);
    urlParams.set('end_date', endDate.value);
    window.history.pushState({}, '', '?' + urlParams.toString());
    window.location.reload();
  };

  // Add event listeners for the filter inputs
  clientSelect.addEventListener('change', updateFiltersAndSort);
  categorySelect.addEventListener('change', updateFiltersAndSort);
  modeSelect.addEventListener('change', updateFiltersAndSort);
  startDate.addEventListener('change', updateFiltersAndSort);
  endDate.addEventListener('change', updateFiltersAndSort);
});
</script>
@endsection
