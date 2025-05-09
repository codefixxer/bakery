@extends('frontend.layouts.app')

@section('title', 'Returned Goods')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Header -->
  <div class="page-header d-flex align-items-center mb-4" style="background-color: #041930; border-radius: 0.75rem; padding: 1rem 2rem;">
    <i class="bi bi-arrow-counterclockwise me-2 fs-3" style="color: #e2ae76;"></i>
    <h2 class="mb-0 fw-bold" style="color: #e2ae76;">Returned Goods</h2>
  </div>

  {{-- FILTERS --}}
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
      <label class="form-label">Client</label>
      <select name="client_id" class="form-select">
        <option value="">All clients</option>
        @foreach($clients as $c)
          <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>
            {{ $c->name }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">From</label>
      <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">To</label>
      <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button type="submit" class="btn btn-gold-blue w-100">
        <i class="bi bi-funnel me-1"></i> Apply Filters
      </button>
    </div>
  </form>

  {{-- SUMMARY CARDS --}}
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card text-white h-100" style="background-color: #041930;">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-box-seam fs-2 me-3" style="color: #e2ae76;"></i>
          <div>
            <div class="fs-5" style="color: #e2ae76;">Total Supplies</div>
            <div class="fs-4" style="color: #e2ae76;">€{{ number_format($grandSupply, 2) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white h-100" style="background-color: #041930;">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-arrow-counterclockwise fs-2 me-3" style="color: #e2ae76;"></i>
          <div>
            <div class="fs-5" style="color: #e2ae76;">Total Returns</div>
            <div class="fs-4" style="color: #e2ae76;">€{{ number_format($grandReturn, 2) }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white h-100" style="background-color: #041930;">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-cash-stack fs-2 me-3" style="color: #e2ae76;"></i>
          <div>
            <div class="fs-5" style="color: #e2ae76;">Net Income</div>
            <div class="fs-4" style="color: #e2ae76;">€{{ number_format($grandNet, 2) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- TWO-COLUMN LAYOUT --}}
  <div class="row g-4">
    {{-- A) All Supplies --}}
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header" style="background-color: #041930; color: #e2ae76; font-weight: bold;">
          All Supplies
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
              <thead style="background-color: #e2ae76; color: #041930;">
                <tr>
                  
                  <th>Date</th>
                  <th>Client</th>
                  <th>External Supply (€)</th>
                  <th>Return (€)</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($returnedGoods as $r)
                  <tr>
                   
                    <td>{{ $r->return_date->format('Y-m-d') }}</td>
                    <td>{{ $r->client->name }}</td>
                    <td>€{{ number_format($r->externalSupply->total_amount ?? 0, 2) }}</td>
                    <td>€{{ number_format($r->total_amount, 2) }}</td>
                    <td class="text-end">
                      <a href="{{ route('returned-goods.edit', $r->id) }}" class="btn btn-sm btn-gold">Edit</a>
                      <form action="{{ route('returned-goods.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this return?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-red">Delete</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted">No supplies found.</td>
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
        <div class="card-header" style="background-color: #041930; color: #e2ae76; font-weight: bold;">
          Daily Comparison (Sold - Returned)
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
              <thead style="background-color: #e2ae76; color: #041930;">
                <tr>
                  <th>Created by</th>
                  <th>Date</th>
                  <th>Supply (€)</th>
                  <th>Returned (€)</th>
                  <th>Net (€)</th>
                </tr>
              </thead>
              <tbody>
                @forelse($report as $row)
                  <tr>
                    <td>
                      <span class="badge bg-light text-dark">
                        {{ $r->user->name ?? '—' }}
                      </span>
                    </td>
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
                    <td colspan="4" class="text-center text-muted">No data to compare.</td>
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

<style>
  .btn-gold-blue {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    border: 1px solid #e2ae76;
  }
  .btn-gold-blue:hover {
    background-color: #d89d5c !important;
    color: white !important;
  }
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #e2ae76 !important;
    background-color: transparent !important;
  }
  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: white !important;
  }
  .btn-red {
    border: 1px solid red !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: red !important;
    color: white !important;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const clientSelect = document.querySelector('[name="client_id"]');
  const startDate = document.querySelector('[name="start_date"]');
  const endDate = document.querySelector('[name="end_date"]');

  const updateFiltersAndSort = () => {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('client_id', clientSelect.value);
    urlParams.set('start_date', startDate.value);
    urlParams.set('end_date', endDate.value);
    window.location.href = '?' + urlParams.toString();
  };

  clientSelect.addEventListener('change', updateFiltersAndSort);
  startDate.addEventListener('change', updateFiltersAndSort);
  endDate.addEventListener('change', updateFiltersAndSort);
});
</script>
@endsection
