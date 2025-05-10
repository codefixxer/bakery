{{-- resources/views/frontend/showcase/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Showcases')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Header Card with "New Showcase" button -->
  <div class="card mb-4 border-primary shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #041930;">
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        <i class="bi bi-calendar-day me-2"></i>Daily Showcases
      </h5>
      <a href="{{ route('showcase.create') }}" class="btn btn-gold">
        <i class="bi bi-plus-circle me-1"></i> New Showcase
      </a>
    </div>
    <div class="card-body">
      <p class="mb-0 text-muted">Browse and manage all your saved showcases below.</p>
    </div>
  </div>

  <!-- Showcases Table Card -->
  <div class="card border-primary shadow-sm">
    <div class="card-body table-responsive">
      <table
        id="showcasesTable"
        class="table table-bordered table-striped table-hover align-middle text-center mb-0"
        style="width:100%;"
      >
        <thead>
          <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Break-even (€)</th>
            <th>Total Revenue (€)</th>
            <th>Potential Avg (€)</th>
            <th>Plus (€)</th>
            <th>Real Margin (%)</th>
            <th>Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($showcases as $s)
            <tr>
              <td>{{ \Carbon\Carbon::parse($s->showcase_date)->format('Y-m-d') }}</td>
              <td>{{ $s->showcase_name }}</td>
              <td>€{{ number_format($s->break_even, 2) }}</td>
              <td>€{{ number_format($s->total_revenue, 2) }}</td>
              <td>€{{ number_format($s->potential_income_average, 2) }}</td>
              <td>€{{ number_format($s->plus, 2) }}</td>
              <td>
                @if($s->real_margin >= 0)
                  <span class="text-success">{{ $s->real_margin }}%</span>
                @else
                  <span class="text-danger">{{ $s->real_margin }}%</span>
                @endif
              </td>
              <td>{{ optional($s->updated_at)->format('Y-m-d') }}</td>
              <td>
                <a href="{{ route('showcase.show', $s) }}" class="btn btn-sm btn-deepblue me-1" title="View">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('showcase.edit', $s) }}" class="btn btn-sm btn-gold me-1" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('showcase.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this showcase?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-red" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-muted">No showcases found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

<style>
  table th {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    text-align: center;
    vertical-align: middle;
  }
  table td {
    text-align: center;
    vertical-align: middle;
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
  .btn-deepblue {
    border: 1px solid #041930 !important;
    color: #041930 !important;
    background-color: transparent !important;
  }
  .btn-deepblue:hover {
    background-color: #041930 !important;
    color: white !important;
  }
  .btn-red {
    border: 1px solid #ff0000 !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: #ff0000 !important;
    color: white !important;
  }
</style>

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (!window.$ || !$.fn.DataTable) return;

    $('#showcasesTable').DataTable({
      paging:     true,
      ordering:   true,
      responsive: true,
      pageLength: 10,
      order:      [[0, 'desc']],       // sort by Date descending
      columns: [
        null,  // Date
        null,  // Name
        null,  // Break-even
        null,  // Total Revenue
        null,  // Potential Avg
        null,  // Plus
        null,  // Real Margin
        null,  // Updated
        { orderable: false }  // Actions
      ],
      language: {
        search:      "Search:",
        lengthMenu: "Show _MENU_ entries per page",
        info:       "Showing _START_ to _END_ of _TOTAL_ showcases",
        paginate: {
          previous: "&laquo;",
          next:     "&raquo;"
        },
        zeroRecords: "No matching showcases found"
      }
    });
  });
</script>
@endsection
