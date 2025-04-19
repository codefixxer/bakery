{{-- resources/views/frontend/production/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Production Records')

@section('content')
@php
    // Build unique, sorted lists for recipes and chefs
    $allRecipes = $productions
        ->flatMap(fn($p) => $p->details->pluck('recipe.recipe_name'))
        ->unique()
        ->sort();

    $allChefs = $productions
        ->flatMap(fn($p) => $p->details->pluck('chef.name'))
        ->unique()
        ->sort();
@endphp

<div class="container py-5">

  {{-- 1) Search / Filter Card --}}
  <div class="card mb-4 shadow-sm filter-card">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        
        {{-- Recipe Multi‑Select --}}
        <div class="col-6 col-md-3">
          <label class="form-label small mb-1">Recipe</label>
          <div class="dropdown">
            <button class="btn btn-outline-primary w-100 text-start dropdown-toggle" data-bs-toggle="dropdown">
              <i class="bi bi-journal-bookmark me-1"></i> Select Recipe(s)
            </button>
            <div class="dropdown-menu p-3">
              @foreach($allRecipes as $recipeName)
                @php $slug = strtolower(\Illuminate\Support\Str::slug($recipeName, '_')); @endphp
                <div class="form-check mb-1">
                  <input class="form-check-input recipe-checkbox" type="checkbox" value="{{ strtolower($recipeName) }}" id="recipeCheckbox_{{ $slug }}">
                  <label class="form-check-label" for="recipeCheckbox_{{ $slug }}">{{ $recipeName }}</label>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Chef Multi‑Select --}}
        <div class="col-6 col-md-3">
          <label class="form-label small mb-1">Chef</label>
          <div class="dropdown">
            <button class="btn btn-outline-success w-100 text-start dropdown-toggle" data-bs-toggle="dropdown">
              <i class="bi bi-person-lines-fill me-1"></i> Select Chef(s)
            </button>
            <div class="dropdown-menu p-3">
              @foreach($allChefs as $chefName)
                @php $slug = strtolower(\Illuminate\Support\Str::slug($chefName, '_')); @endphp
                <div class="form-check mb-1">
                  <input class="form-check-input chef-checkbox" type="checkbox" value="{{ strtolower($chefName) }}" id="chefCheckbox_{{ $slug }}">
                  <label class="form-check-label" for="chefCheckbox_{{ $slug }}">{{ $chefName }}</label>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Equipment --}}
        <div class="col-6 col-md-3">
          <label class="form-label small mb-1">Equipment</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-tools"></i></span>
            <input type="text" id="filterEquipment" class="form-control" placeholder="Equipment name…">
          </div>
        </div>

        {{-- From Date --}}
        <div class="col-6 col-md-3">
          <label class="form-label small mb-1">From Date</label>
          <input type="date" id="filterStartDate" class="form-control">
        </div>

        {{-- To Date --}}
        <div class="col-6 col-md-3">
          <label class="form-label small mb-1">To Date</label>
          <input type="date" id="filterEndDate" class="form-control">
        </div>

      </div>

      {{-- Total Potential Revenue Card --}}
      <div class="row mt-4">
        <div class="col-12">
          <div class="card bg-info text-white shadow-sm rounded-3 revenue-card">
            <div class="card-body d-flex justify-content-between align-items-center py-3 px-4">
              <div class="d-flex align-items-center">
                <i class="bi bi-cash-stack fs-2 me-3"></i>
                <div>
                  <div class="small text-opacity-75">Total Potential</div>
                  <div class="h5 fw-semibold mb-0">Revenue</div>
                </div>
              </div>
              <div id="totalRevenue" class="h3 fw-bold">$0.00</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- 2) Production Cards --}}
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4" id="productionsContainer">
    @foreach($productions as $production)
      @php
        $recipeNames   = $production->details->pluck('recipe.recipe_name')->implode(', ');
        $chefNames     = $production->details->pluck('chef.name')->implode(', ');
        $equipmentList = $production->details
                            ->flatMap(fn($d) => is_array($d->equipment_ids) ? $d->equipment_ids : [])
                            ->unique()
                            ->map(fn($id) => $equipmentMap[$id] ?? $id)
                            ->implode(', ');
      @endphp
      <div class="col production-card"
           data-recipes="{{ strtolower($recipeNames) }}"
           data-chefs="{{ strtolower($chefNames) }}"
           data-equipment="{{ strtolower($equipmentList) }}"
           data-date="{{ $production->production_date }}"
           data-potential="{{ $production->total_potential_revenue }}">
        <div class="card h-100">
          <div class="card-header bg-primary text-white d-flex justify-content-between">
            <div>
              <h6 class="mb-0">{{ $production->production_date }}</h6>
              <small>{{ $production->details->count() }} item{{ $production->details->count() > 1 ? 's' : '' }}</small>
            </div>
            <span class="badge bg-light text-primary rounded-pill">
              {{ $production->details->count() }}
            </span>
          </div>
          <div class="card-body">
            <p class="mb-2">
              <strong>Potential:</strong>
              ${{ number_format($production->total_potential_revenue, 2) }}
            </p>
            <ul class="list-unstyled small">
              @foreach($production->details as $detail)
                <li class="mb-2">
                  <i class="bi bi-box-seam me-1"></i>
                  {{ $detail->recipe->recipe_name }}
                  <span class="float-end">{{ $detail->quantity }}</span>
                  <br>
                  <small class="text-muted">
                    Chef: {{ $detail->chef->name }} &bull; Time: {{ $detail->execution_time }}m
                  </small>
                </li>
              @endforeach
            </ul>
          </div>
          <div class="card-footer bg-light small d-flex justify-content-between">
            <span>Updated: {{ $production->updated_at->format('Y-m-d') }}</span>
            <div>
              <a href="{{ route('production.edit', $production) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('production.destroy', $production) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this record?')">
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

@section('styles')
<style>
  .filter-card { background: #fff; border: 1px solid #e0e0e0; border-radius: .75rem; }
  .filter-card .dropdown-menu { border-radius: .5rem; }
  .production-card .card { transition: transform .2s, box-shadow .2s; border-radius: .75rem; }
  .production-card .card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,.12); }
  .revenue-card { background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%); }
  .revenue-card:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); }
  .form-check-input:checked + .form-check-label { font-weight: 600; color: #2c3e50; }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const recipeCBs = document.querySelectorAll('.recipe-checkbox');
  const chefCBs   = document.querySelectorAll('.chef-checkbox');
  const equipIn   = document.getElementById('filterEquipment');
  const startIn   = document.getElementById('filterStartDate');
  const endIn     = document.getElementById('filterEndDate');
  const cards     = document.querySelectorAll('.production-card');
  const totalRev  = document.getElementById('totalRevenue');

  function filterCards() {
    const selRec = [...recipeCBs].filter(cb => cb.checked).map(cb => cb.value);
    const selCh  = [...chefCBs].filter(cb => cb.checked).map(cb => cb.value);
    const eq     = equipIn.value.trim().toLowerCase();
    const from   = startIn.value;
    const to     = endIn.value;

    let sum = 0;

    cards.forEach(card => {
      card.style.display = '';
      const recs  = card.dataset.recipes;
      const chefs = card.dataset.chefs;
      const eqs   = card.dataset.equipment;
      const date  = card.dataset.date;
      const pot   = parseFloat(card.dataset.potential) || 0;

      const okR = selRec.length === 0 || selRec.some(r => recs.includes(r));
      const okC = selCh.length  === 0 || selCh.some(c => chefs.includes(c));
      const okE = !eq || eqs.includes(eq);
      let   okD = true;
      if (from) okD = okD && (date >= from);
      if (to)   okD = okD && (date <= to);

      if (okR && okC && okE && okD) sum += pot;
      else card.style.display = 'none';
    });

    totalRev.textContent = `$${sum.toFixed(2)}`;
  }

  recipeCBs.forEach(cb => cb.addEventListener('change', filterCards));
  chefCBs.forEach(cb   => cb.addEventListener('change', filterCards));
  equipIn.addEventListener('input', filterCards);
  startIn.addEventListener('change', filterCards);
  endIn.addEventListener('change', filterCards);

  // show all & calculate on load
  cards.forEach(c => c.style.display = '');
  filterCards();
});
</script>
@endsection