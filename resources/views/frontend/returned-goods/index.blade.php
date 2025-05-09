@extends('frontend.layouts.app')

@section('title','Returned Goods')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Header -->
  <div class="page-header d-flex align-items-center mb-4"
       style="background-color: #041930; border-radius: .75rem; padding: 1rem 2rem;">
    <i class="bi bi-arrow-counterclockwise me-2 fs-3" style="color: #e2ae76;"></i>
    <h2 class="mb-0 fw-bold" style="color: #e2ae76;">Returned Goods</h2>
  </div>

  {{-- Filters --}}
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
      <label class="form-label">Client</label>
      <select name="client_id" class="form-select">
        <option value="">All clients</option>
        @foreach($clients as $c)
          <option value="{{ $c->id }}" {{ request('client_id')==$c->id?'selected':'' }}>
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
        <i class="bi bi-funnel me-1"></i> Apply Filters
      </button>
    </div>
  </form>

  {{-- Summary Cards --}}
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card text-white h-100 bg-success">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-box-seam fs-2 me-3"></i>
          <div>
            <div class="fs-5">Total Supplies</div>
            <div class="fs-4">€{{ number_format($grandSupply,2) }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-white h-100 bg-danger">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-arrow-counterclockwise fs-2 me-3"></i>
          <div>
            <div class="fs-5">Total Returns</div>
            <div class="fs-4">€{{ number_format($grandReturn,2) }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-white h-100 bg-primary">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-cash-stack fs-2 me-3"></i>
          <div>
            <div class="fs-5">Net Income</div>
            <div class="fs-4">€{{ number_format($grandNet,2) }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- All Supplies vs Returns --}}
  <div class="row g-4">
    {{-- A) Supplies Table --}}
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header bg-dark text-gold fw-bold">All Supplies</div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped mb-0">
              <thead class="bg-gold text-dark">
                <tr>
                  <th>Date</th><th>Client</th><th>Supply (€)</th>
                  <th>Returned (€)</th><th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($supplies as $s)
                  <tr>
                    <td>{{ $s->supply_date->format('Y-m-d') }}</td>
                    <td>{{ $s->client->name }}</td>
                    <td>€{{ number_format($s->total_amount,2) }}</td>
                    <td>€{{ number_format($returnsBySupply[$s->id] ?? 0,2) }}</td>
                    <td class="text-end">
                      <a href="{{ route('returned-goods.create',['external_supply_id'=>$s->id]) }}"
                         class="btn btn-sm btn-outline-warning me-1">
                        <i class="bi bi-arrow-counterclockwise"></i>
                      </a>
                      <a href="{{ route('external-supplies.show',$s->id) }}"
                         class="btn btn-sm btn-outline-info me-1">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="{{ route('external-supplies.edit',$s->id) }}"
                         class="btn btn-sm btn-outline-primary me-1">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <form action="{{ route('external-supplies.destroy',$s->id) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Delete this supply?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center text-muted">No supplies found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- B) Daily Comparison --}}
    <div class="col-lg-6">
      <div class="card h-100">
        <div class="card-header bg-dark text-gold fw-bold">
          Daily Comparison (Sold - Returned)
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped mb-0">
              <thead class="bg-gold text-dark">
                <tr>
                  <th>Date</th><th>Supply (€)</th>
                  <th>Returned (€)</th><th>Net (€)</th>
                </tr>
              </thead>
              <tbody>
                @forelse($supsByDate as $d)
                  <tr>
                    <td>{{ $d->date }}</td>
                    <td>€{{ number_format($d->total_supply,2) }}</td>
                    <td class="{{ $d->total_return>0?'text-danger':'text-success' }}">
                      €{{ number_format($d->total_return,2) }}
                    </td>
                    <td class="{{ ($d->total_supply-$d->total_return)>=0?'text-success':'text-danger' }}">
                      €{{ number_format($d->total_supply-$d->total_return,2) }}
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
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const clientSelect = document.querySelector('[name="client_id"]');
  const startDate    = document.querySelector('[name="start_date"]');
  const endDate      = document.querySelector('[name="end_date"]');

  const updateFilters = () => {
    const params = new URLSearchParams(window.location.search);
    params.set('client_id', clientSelect.value || '');
    params.set('start_date', startDate.value || '');
    params.set('end_date', endDate.value || '');
    window.location.search = params.toString();
  };

  clientSelect.addEventListener('change', updateFilters);
  startDate.   addEventListener('change', updateFilters);
  endDate.     addEventListener('change', updateFilters);
});
</script>
@endsection
