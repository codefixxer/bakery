{{-- resources/views/frontend/showcase/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Showcases')

@section('content')
<div class="container py-5">
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Daily Showcases</h5>
      <p class="card-text">Browse and manage all your saved showcases below.</p>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table id="showcasesTable" class="table table-striped table-hover table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>Date</th>
              {{-- <th>Recipes</th> --}}
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
            @foreach($showcases as $s)
              <tr>
                <td>{{ $s->showcase_date }}</td>
                {{-- <td>
                  <div class="d-flex flex-wrap gap-2">
                    @forelse($s->recipes as $sr)
                      @php
                        $rec = $sr->recipe;
                        $dept = $rec->department->name ?? '—';
                      @endphp
                      <div class="border rounded p-2 bg-light" style="min-width: 6rem;">
                        <div class="fw-semibold">{{ $rec->recipe_name }}</div>
                        <div class="text-muted small">{{ $dept }}</div>
                      </div>
                    @empty
                      <em>No recipes</em>
                    @endforelse
                  </div>
                </td> --}}
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
                <td>{{ $s->created_at->format('Y-m-d') }}</td>
                <td>{{ $s->updated_at->format('Y-m-d') }}</td>
                <td class="text-center">
                  <a href="{{ route('showcase.edit', $s->id) }}"
                     class="btn btn-sm btn-outline-primary me-1"
                     title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="{{ route('showcase.show', $s->id) }}"
                     class="btn btn-sm btn-outline-primary me-1"
                     title="Edit">
                     <i class="bi bi-eye me-1"></i>
                    </a>


                  


                  <form action="{{ route('showcase.destroy', $s->id) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Delete this showcase?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  if (window.$ && $.fn.DataTable) {
    $('#showcasesTable').DataTable({
      pageLength: 10,
      responsive: true,
      autoWidth: false,
      order: [[0, 'desc']],
      columnDefs: [
        { orderable: false, targets: 8 }  // Actions column
      ]
    });
  }
});
</script>
@endsection
