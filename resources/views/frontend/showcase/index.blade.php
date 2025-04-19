@extends('frontend.layouts.app')

@section('title', 'All Showcases')

@section('content')
<div class="container py-5">

  {{-- 1) Filter Card --}}
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        {{-- Department Filter --}}
        <div class="col-12 col-md-4">
          <label for="searchDept" class="form-label small mb-1">Department</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="searchDept" class="form-control" placeholder="Department name…">
          </div>
        </div>

        {{-- From Date --}}
        <div class="col-6 col-md-4">
          <label for="filterStartDate" class="form-label small mb-1">From Date</label>
          <input type="date" id="filterStartDate" class="form-control">
        </div>

        {{-- To Date --}}
        <div class="col-6 col-md-4">
          <label for="filterEndDate" class="form-label small mb-1">To Date</label>
          <input type="date" id="filterEndDate" class="form-control">
        </div>
      </div>
    </div>
  </div>

  {{-- 2) Showcase Cards --}}
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-4 mt-3" id="showcasesContainer">
    @foreach($showcases as $s)
      @php
        $deptName = $s->department ? $s->department->name : 'No Dept';
        $created  = $s->created_at->format('Y-m-d'); // For filtering
      @endphp

      <div class="col showcase-card"
           data-dept="{{ strtolower($deptName) }}"
           data-created="{{ $created }}">
        <div class="card h-100 shadow-sm">

          {{-- Header --}}
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0">{{ $deptName }}</h5>
              <small class="text-light">
                Showcase Date: {{ $s->showcase_date ?? 'N/A' }}
              </small>
            </div>
          </div>

          {{-- Body --}}
          <div class="card-body">
            <dl class="row small mb-3">
              <dt class="col-6">Break-even</dt>
              <dd class="col-6 text-end">
                ${{ number_format($s->break_even,2) }}
              </dd>

              <dt class="col-6">Total Rev</dt>
              <dd class="col-6 text-end">
                ${{ number_format($s->total_revenue,2) }}
              </dd>

              <dt class="col-6">Plus</dt>
              <dd class="col-6 text-end">
                ${{ number_format($s->plus,2) }}
              </dd>

              <dt class="col-6">Margin</dt>
              <dd class="col-6 text-end">
                @if($s->real_margin >= 0)
                  <span class="text-success">{{ $s->real_margin }}%</span>
                @else
                  <span class="text-danger">{{ $s->real_margin }}%</span>
                @endif
              </dd>

              <dt class="col-6">Potential Avg</dt>
              <dd class="col-6 text-end">
                ${{ number_format($s->potential_income_average,2) }}
              </dd>
            </dl>

            <h6 class="fw-semibold">Recipes</h6>
            <ul class="list-group list-group-flush small">
              @forelse($s->recipes as $sr)
                {{-- For each ShowcaseRecipe: show recipe name, quantity, plus potential or actual? --}}
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                  <div>
                    {{ $sr->recipe->recipe_name ?? 'No Name' }}
                    @if($sr->quantity)
                      <span class="text-muted">x{{ $sr->quantity }}</span>
                    @endif
                  </div>
                  <span class="badge bg-secondary">
                    ${{ number_format($sr->actual_revenue,2) }}
                  </span>
                </li>
              @empty
                <li class="list-group-item px-0">
                  <em>No recipes found.</em>
                </li>
              @endforelse
            </ul>
          </div>

          {{-- Footer --}}
          <div class="card-footer text-muted small d-flex justify-content-between align-items-center">
            <div>
              <span>Created: {{ $s->created_at->format('Y-m-d') }}</span><br>
              <span>Updated: {{ $s->updated_at->format('Y-m-d') }}</span>
            </div>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('showcase.edit', $s->id) }}" class="btn btn-outline-primary" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('showcase.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Delete this showcase?');">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const deptInput     = document.getElementById('searchDept');
  const startDateInput = document.getElementById('filterStartDate');
  const endDateInput   = document.getElementById('filterEndDate');
  const showcaseCards  = document.querySelectorAll('.showcase-card');

  function filterShowcases() {
    const deptVal   = deptInput.value.trim().toLowerCase();
    const startVal  = startDateInput.value; // e.g. '2025-04-12'
    const endVal    = endDateInput.value;

    showcaseCards.forEach(card => {
      const cardDept   = card.dataset.dept;         // e.g. 'bakery dept'
      const cardDate   = card.dataset.created;      // e.g. '2025-04-10'

      // text match
      const matchDept = !deptVal || cardDept.includes(deptVal);

      // date match
      let matchDate = true;
      if (startVal && cardDate < startVal) matchDate = false;
      if (endVal   && cardDate > endVal)   matchDate = false;

      const visible = matchDept && matchDate;
      card.style.display = visible ? '' : 'none';
    });
  }

  // restrict min / max if user picks start date after end date
  startDateInput.addEventListener('change', () => {
    endDateInput.min = startDateInput.value;
    if (endDateInput.value && endDateInput.value < startDateInput.value) {
      endDateInput.value = startDateInput.value;
    }
    filterShowcases();
  });
  endDateInput.addEventListener('change', filterShowcases);

  deptInput.addEventListener('input', filterShowcases);

  // Optionally call filterShowcases() once on load if needed
  // filterShowcases();
});
</script>
@endsection
