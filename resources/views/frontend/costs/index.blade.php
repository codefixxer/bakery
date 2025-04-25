{{-- resources/views/frontend/costs/index.blade.php --}}
@extends('frontend.layouts.app')
@section('title','All Costs')

@section('content')
<div class="container py-5">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">All Costs</h4>
    <a href="{{ route('costs.create') }}" class="btn btn-success">
      <i class="bi bi-plus-circle me-1"></i> Add Cost
    </a>
  </div>

  {{-- Month Filter --}}
  <form method="GET" class="row g-2 align-items-end mb-4">
    @php
      [$year, $month] = explode('-', $filter);
    @endphp
    <div class="col-auto">
      <label for="filterMonth" class="form-label">Show month</label>
      <input type="month" id="filterMonth" name="filter_month"
             class="form-control"
             value="{{ old('filter_month',$filter) }}"
             onchange="this.form.submit()">
    </div>
  </form>

  <div class="card basic-data-table">
    <div class="table-responsive">
      <table id="costTable" class="table mb-0" data-page-length="10">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Identifier</th>       {{-- new --}}
            <th>Supplier</th>
            <th>Amount</th>
            <th>Due Date</th>
            <th>Category</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($costs as $cost)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $cost->cost_identifier }}</td>  {{-- new --}}
              <td>{{ $cost->supplier }}</td>
              <td>${{ number_format($cost->amount,2) }}</td>
              <td>{{ \Carbon\Carbon::parse($cost->due_date)->format('Y‑m‑d') }}</td>
              <td>{{ $cost->category->name ?? '–' }}</td>
              <td class="text-center">
                <a href="{{ route('costs.edit',$cost) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('costs.destroy',$cost) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Delete this cost?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted">No costs found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  if (window.$ && $.fn.DataTable) {
    $('#costTable').DataTable({
      pageLength: $('#costTable').data('page-length'),
      responsive: true,
      scrollX: true,
      autoWidth: false,
      columnDefs: [{ orderable: false, targets: 6 }]
    });
  }
});
</script>
@endsection
