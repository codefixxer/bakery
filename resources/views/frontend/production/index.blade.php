{{-- resources/views/frontend/production/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Production Records')

<style>
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
    border: 1px solid red !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: red !important;
    color: white !important;
  }

  .page-header {
    background-color: #041930;
    color: #e2ae76;
    padding: 1rem 2rem;
    border-radius: 0.75rem;
    margin-bottom: 2rem;
    font-size: 2rem;
    font-weight: bold;
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .page-header i {
    font-size: 2rem;
    color: #e2ae76;
  }

  .filter-chip {
    display: inline-block;
    background: #e2ae76;
    color: #041930;
    padding: .25em .6em;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 0.25rem;
    margin-top: 0.25rem;
  }

  .revenue-card {
    background: linear-gradient(to right, #041930 0%, #e2ae76 100%);
    color: #fff;
    border-radius: 0.75rem;
  }

  .revenue-card .card-body i {
    color: #e2ae76;
  }

  .revenue-card .h5,
  .revenue-card .h3 {
    color: #fff;
  }

  .filter-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: .75rem;
  }
  .filter-card .dropdown-menu {
    max-height: 200px;
    overflow-y: auto;
    border-radius: .5rem;
  }
  .production-table {
    border-radius: .5rem;
    overflow: hidden;
  }
  .production-table thead th {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    text-align: center;
    vertical-align: middle;
  }
  .production-table tbody td {
    text-align: center;
    vertical-align: middle;
  }
  .production-table tbody tr:hover {
    background: rgba(13,110,253,.05);
  }
  .detail-row td {
    background: #fafafa;
  }
  .toggle-btn i {
    transition: transform .2s;
  }
  .toggle-btn.open i {
    transform: rotate(90deg);
  }
</style>

@section('content')
@php
  use Illuminate\Support\Str;
  $allRecipes = $productions->flatMap(fn($p) => $p->details->pluck('recipe.recipe_name'))->unique()->sort();
  $allChefs    = $productions->flatMap(fn($p) => $p->details->pluck('chef.name'))->unique()->sort();
@endphp

<div class="container py-5">
  <div class="page-header">
    <i class="bi bi-gear-fill"></i>
    Production Records
  </div>

  <div class="card mb-4 shadow-sm filter-card p-3">
    <div class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label small">Recipe</label>
        <div class="dropdown">
          <button class="btn btn-outline-primary w-100 text-start dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-journal-bookmark me-1"></i> Recipes
          </button>
          <div class="dropdown-menu p-3">
            @foreach($allRecipes as $r)
              @php $slug = Str::slug($r,'_') @endphp
              <div class="form-check mb-1">
                <input class="form-check-input recipe-checkbox" type="checkbox" value="{{ strtolower($r) }}" id="recipe_{{ $slug }}">
                <label class="form-check-label" for="recipe_{{ $slug }}">{{ $r }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <label class="form-label small">Chef</label>
        <div class="dropdown">
          <button class="btn btn-outline-success w-100 text-start dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-person-lines-fill me-1"></i> Chefs
          </button>
          <div class="dropdown-menu p-3">
            @foreach($allChefs as $c)
              @php $slug = Str::slug($c,'_') @endphp
              <div class="form-check mb-1">
                <input class="form-check-input chef-checkbox" type="checkbox" value="{{ strtolower($c) }}" id="chef_{{ $slug }}">
                <label class="form-check-label" for="chef_{{ $slug }}">{{ $c }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <label class="form-label small">Equipment</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-tools"></i></span>
          <input type="text" id="filterEquipment" class="form-control" placeholder="Type to filter…">
        </div>
      </div>

      <div class="col-md-3">
        <label class="form-label small">From</label>
        <input type="date" id="filterStartDate" class="form-control">
      </div>

      <div class="col-md-3">
        <label class="form-label small">To</label>
        <input type="date" id="filterEndDate" class="form-control">
      </div>
    </div>

    <div id="activeFilters" class="mt-3"></div>

    <div class="row mt-4">
      <div class="col-12">
        <div class="card shadow-sm revenue-card">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
              <i class="bi bi-cash-stack fs-2 me-3"></i>
              <div>
                <div class="small" style="color: white">Total Potential</div>
                <div class="h5 fw-bold mb-0">Revenue</div>
              </div>
            </div>
            <div id="totalRevenue" class="h3 fw-bold">$0.00</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm production-table">
    <div class="card-body table-responsive p-0">
      <table class="table mb-0" id="productionTable">
        <thead>
          <tr>
            <th style="width:48px"></th>
            <th>Date</th>
            <th>Items</th>
            <th>Potential</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($productions as $p)
            @php
              $equipNames = collect(explode(',', $p->details->pluck('equipment_ids')->join(',')))
                            ->filter()
                            ->unique()
                            ->map(fn($id) => $equipmentMap[$id] ?? '')
                            ->filter()
                            ->implode(', ');
            @endphp

            <tr class="prod-row"
                data-recipes="{{ strtolower($p->details->pluck('recipe.recipe_name')->join(' ')) }}"
                data-chefs="{{ strtolower($p->details->pluck('chef.name')->join(' ')) }}"
                data-equipment="{{ strtolower($equipNames) }}"
                data-date="{{ $p->production_date }}">
              <td>
                <button class="btn btn-sm btn-outline-secondary toggle-btn">
                  <i class="bi bi-caret-right-fill"></i>
                </button>
              </td>
              <td>{{ $p->production_date }}</td>
              <td>{{ $p->details->count() }}</td>
              <td data-potential="{{ $p->total_potential_revenue }}">
                ${{ number_format($p->total_potential_revenue, 2) }}
              </td>
              <td class="text-center">
                <a href="{{ route('production.show', $p) }}" class="btn btn-sm btn-deepblue me-1" title="View">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('production.edit', $p) }}" class="btn btn-sm btn-gold me-1" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('production.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-red" title="Delete">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>

            <tr class="detail-row" style="display:none">
              <td colspan="5" class="p-3">
                <ul class="mb-0">
                  @foreach($p->details as $d)
                    @php
                      $ids   = array_filter(explode(',', $d->equipment_ids));
                      $names = collect($ids)->map(fn($id) => $equipmentMap[$id] ?? '')->filter()->implode(', ');
                    @endphp
                    <li>
                      <strong>{{ $d->recipe->recipe_name }}</strong>
                      × {{ $d->quantity }} — Chef: {{ $d->chef->name }}, {{ $d->execution_time }}m,
                      <i class="bi bi-tools"></i> {{ $names ?: '—' }}
                    </li>
                  @endforeach
                </ul>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const recipeCBs    = document.querySelectorAll('.recipe-checkbox');
  const chefCBs      = document.querySelectorAll('.chef-checkbox');
  const equipInput   = document.getElementById('filterEquipment');
  const startInput   = document.getElementById('filterStartDate');
  const endInput     = document.getElementById('filterEndDate');
  const rows         = document.querySelectorAll('#productionTable .prod-row');
  const detailRows   = document.querySelectorAll('#productionTable .detail-row');
  const totalRevElem = document.getElementById('totalRevenue');
  const activeTags   = document.getElementById('activeFilters');

  // Hide details when toggling
  document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const tr = btn.closest('tr');
      const det = tr.nextElementSibling;
      btn.classList.toggle('open');
      det.style.display = det.style.display === 'none' ? '' : 'none';
    });
  });

  function updateActiveFilters(recipes, chefs, equip, from, to) {
    activeTags.innerHTML = '';
    recipes.forEach(r => {
      const span = document.createElement('span');
      span.className = 'filter-chip';
      span.textContent = r;
      activeTags.appendChild(span);
    });
    chefs.forEach(c => {
      const span = document.createElement('span');
      span.className = 'filter-chip';
      span.textContent = c;
      activeTags.appendChild(span);
    });
    if (equip) {
      const span = document.createElement('span');
      span.className = 'filter-chip';
      span.textContent = equip;
      activeTags.appendChild(span);
    }
    if (from || to) {
      const span = document.createElement('span');
      span.className = 'filter-chip';
      span.textContent = `${from || '...'} → ${to || '...'}`;
      activeTags.appendChild(span);
    }
  }

  function filterTable() {
    const selRecipes = [...recipeCBs].filter(cb => cb.checked).map(cb => cb.value);
    const selChefs   = [...chefCBs].filter(cb => cb.checked).map(cb => cb.value);
    const equipVal   = equipInput.value.trim().toLowerCase();
    const fromDate   = startInput.value;
    const toDate     = endInput.value;

    updateActiveFilters(selRecipes, selChefs, equipVal, fromDate, toDate);

    let sum = 0;

    detailRows.forEach(dr => dr.style.display = 'none');

    rows.forEach(row => {
      const recs = row.dataset.recipes;
      const chfs = row.dataset.chefs;
      const eqs  = row.dataset.equipment;
      const date = row.dataset.date;

      const okDate = (!fromDate || date >= fromDate) && (!toDate || date <= toDate);
      const okEq   = !equipVal || eqs.includes(equipVal);
      const okR    = !selRecipes.length || selRecipes.some(r => recs.includes(r));
      const okC    = !selChefs.length   || selChefs.some(c => chfs.includes(c));
      const show   = okDate && okEq && okR && okC;

      row.style.display = show ? '' : 'none';

      if (show) {
        const pCell = row.querySelector('td[data-potential]');
        const val   = pCell ? parseFloat(pCell.dataset.potential) : 0;
        sum += val;
      }
    });

    totalRevElem.textContent = `$${sum.toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })}`;
  }

  // Wire up filters
  recipeCBs.forEach(cb => cb.addEventListener('change', filterTable));
  chefCBs.forEach(cb => cb.addEventListener('change', filterTable));
  equipInput.addEventListener('input', filterTable);
  startInput.addEventListener('change', filterTable);
  endInput.addEventListener('change', filterTable);

  // Initial calculation
  filterTable();
});
</script>
@endsection
