{{-- resources/views/frontend/showcase/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Showcases')

@section('content')
<div class="container py-5">

  <!-- Header Card with "New Showcase" button -->
  <div class="card mb-4 border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0"><i class="bi bi-calendar-day me-2"></i>Daily Showcases</h5>
      <a href="{{ route('showcase.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle me-1 text-primary"></i> New Showcase
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
        class="table table-striped table-hover table-bordered align-middle mb-0"
        style="width:100%;"
      >
        <thead class="table-primary">
          <tr>
            <th>Date</th>
            <th class="text-end">Break-even (€)</th>
            <th class="text-end">Total Revenue (€)</th>
            <th class="text-end">Potential Avg (€)</th>
            <th class="text-end">Plus (€)</th>
            <th class="text-end">Real Margin (%)</th>
            <th>Created</th>
            <th>Updated</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($showcases as $s)
            <tr>
              <td>{{ $s->showcase_date }}</td>
              <td class="text-end">{{ number_format($s->break_even, 2) }}</td>
              <td class="text-end">{{ number_format($s->total_revenue, 2) }}</td>
              <td class="text-end">{{ number_format($s->potential_income_average, 2) }}</td>
              <td class="text-end">{{ number_format($s->plus, 2) }}</td>
              <td class="text-end">
                @if($s->real_margin >= 0)
                  <span class="text-success">{{ $s->real_margin }}%</span>
                @else
                  <span class="text-danger">{{ $s->real_margin }}%</span>
                @endif
              </td>
              <td>{{ optional($s->created_at)->format('Y-m-d') }}</td>
              <td>{{ optional($s->updated_at)->format('Y-m-d') }}</td>
              <td class="text-center">
                <a
                  href="{{ route('showcase.show', $s) }}"
                  class="btn btn-sm btn-outline-info me-1"
                  title="View">
                  <i class="bi bi-eye"></i>
                </a>
                <a
                  href="{{ route('showcase.edit', $s) }}"
                  class="btn btn-sm btn-outline-primary me-1"
                  title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form
                  action="{{ route('showcase.destroy', $s) }}"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('Delete this showcase?');"
                >
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center text-muted">No showcases found.</td>
            </tr>
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
      $('#showcasesTable').DataTable({
        paging: true,
        ordering: true,
        responsive: true,
        pageLength: 10,
        order: [[0, 'desc']],
        columnDefs: [
          { orderable: false, targets: 8 }
        ]
      });
    }
  });
</script>
@endsection
