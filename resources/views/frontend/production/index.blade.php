{{-- resources/views/frontend/production/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Production Records')

<style>
  .btn-gold { border:1px solid #e2ae76!important; color:#e2ae76!important; background:transparent!important; }
  .btn-gold:hover { background:#e2ae76!important; color:#fff!important; }
  .btn-deepblue { border:1px solid #041930!important; color:#041930!important; background:transparent!important; }
  .btn-deepblue:hover { background:#041930!important; color:#fff!important; }
  .btn-red { border:1px solid red!important; color:red!important; background:transparent!important; }
  .btn-red:hover { background:red!important; color:#fff!important; }

  .page-header { background:#041930; color:#e2ae76; padding:1rem 2rem; border-radius:.75rem; margin-bottom:2rem; display:flex; align-items:center; gap:.75rem; font-size:2rem; font-weight:bold; }
  .page-header i { font-size:2rem; }

  .filter-card { background:#fff; border:1px solid #e0e0e0; border-radius:.75rem; }
  .filter-card .dropdown-menu { max-height:200px; overflow-y:auto; border-radius:.5rem; }

  /* CHANGE: allow dropdowns inside the table to overflow */
  .production-table { border-radius:.5rem; overflow: visible; }
  .production-table thead th { background:#e2ae76!important; color:#041930!important; text-align:center; }
  .production-table tbody td { text-align:center; }
  .detail-row td { background:#fafafa; }
  .toggle-btn i { transition:transform .2s; }
  .toggle-btn.open i { transform:rotate(90deg); }
</style>

@section('content')
@php
  use Illuminate\Support\Str;
  $allRecipes = $productions->flatMap(fn($p) => $p->details->pluck('recipe.recipe_name'))->unique()->sort();
  $allChefs   = $productions->flatMap(fn($p) => $p->details->pluck('chef.name'))->unique()->sort();
@endphp

<div class="container py-5">
  <div class="page-header">
    <i class="bi bi-gear-fill"></i>
    <span>Production Records</span>
  </div>

  <div class="card mb-4 shadow-sm filter-card p-3">
    <div class="row g-3 align-items-end">
      <!-- Recipe filter -->
      <div class="col-md-3">
        <label class="form-label small">Recipe</label>
        <div class="dropdown" data-bs-auto-close="outside">
          <button class="btn btn-outline-primary w-100 text-start dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-journal-bookmark me-1"></i> Recipes
          </button>
          <div class="dropdown-menu p-3">
            @foreach($allRecipes as $r)
              @php $slug = Str::slug($r,'_') @endphp
              <div class="form-check mb-1">
                <input class="form-check-input recipe-checkbox"
                       type="checkbox"
                       value="{{ strtolower($r) }}"
                       id="recipe_{{ $slug }}">
                <label class="form-check-label" for="recipe_{{ $slug }}">{{ $r }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Chef filter -->
      <div class="col-md-3">
        <label class="form-label small">Chef</label>
        <div class="dropdown" data-bs-auto-close="outside">
          <button class="btn btn-outline-success w-100 text-start dropdown-toggle" data-bs-toggle="dropdown">
            <i class="bi bi-person-lines-fill me-1"></i> Chefs
          </button>
          <div class="dropdown-menu p-3">
            @foreach($allChefs as $c)
              @php $slug = Str::slug($c,'_') @endphp
              <div class="form-check mb-1">
                <input class="form-check-input chef-checkbox"
                       type="checkbox"
                       value="{{ strtolower($c) }}"
                       id="chef_{{ $slug }}">
                <label class="form-check-label" for="chef_{{ $slug }}">{{ $c }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Equipment & dates -->
      <div class="col-md-3">
        <label class="form-label small">Equipment</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-tools"></i></span>
          <input type="text" id="filterEquipment" class="form-control" placeholder="Type to filter…">
        </div>
      </div>
      <div class="col-md-1">
        <label class="form-label small">From</label>
        <input type="date" id="filterStartDate" class="form-control">
      </div>
      <div class="col-md-1">
        <label class="form-label small">To</label>
        <input type="date" id="filterEndDate" class="form-control">
      </div>
    </div>
  </div>

  <div class="card shadow-sm revenue-card">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div class="small">Total Potential Revenue</div>
      <div id="totalRevenue" class="h3 mb-0">$0.00</div>
    </div>
  </div>

  <div class="card shadow-sm production-table mt-4">
    <div class="card-body p-0">
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
              $rowRecipes = strtolower($p->details->pluck('recipe.recipe_name')->join(' '));
              $rowChefs   = strtolower($p->details->pluck('chef.name')->join(' '));
            @endphp

            <tr class="prod-row"
                data-recipes="{{ $rowRecipes }}"
                data-chefs="{{ $rowChefs }}"
                data-equipment="{{ strtolower($equipNames) }}"
                data-date="{{ $p->production_date }}">
              <td>
                <button class="btn btn-sm btn-outline-secondary toggle-btn">
                  <i class="bi bi-caret-right-fill"></i>
                </button>
              </td>
              <td>{{ $p->production_date }}</td>
              <td>{{ $p->details->count() }}</td>
              <td class="row-potential" data-original="{{ $p->total_potential_revenue }}">
                ${{ number_format($p->total_potential_revenue, 2) }}
              </td>
              <td class="text-center">
                <a href="{{ route('production.show',$p) }}" class="btn btn-sm btn-deepblue me-1"><i class="bi bi-eye"></i></a>
                <a href="{{ route('production.edit',$p) }}" class="btn btn-sm btn-gold me-1"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('production.destroy',$p) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?');">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-red"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>

            <tr class="detail-row" style="display:none">
              <td colspan="5" class="p-3">
                <ul class="mb-0 ps-3">
                  @foreach($p->details as $d)
                    @php
                      $ids   = array_filter(explode(',',$d->equipment_ids));
                      $names = collect($ids)->map(fn($id)=>$equipmentMap[$id] ?? '')->filter()->implode(', ');
                    @endphp
                    <li data-recipe="{{ strtolower($d->recipe->recipe_name) }}"
                        data-potential="{{ $d->potential_revenue }}">
                      <strong>{{ $d->recipe->recipe_name }}</strong> × {{ $d->quantity }}
                      — Chef: {{ $d->chef->name }}, <i class="bi bi-tools"></i> {{ $names ?: '—' }}
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
  const recipeCBs      = Array.from(document.querySelectorAll('.recipe-checkbox'));
  const chefCBs        = Array.from(document.querySelectorAll('.chef-checkbox'));
  const equipmentInput = document.getElementById('filterEquipment');
  const startDateInput = document.getElementById('filterStartDate');
  const endDateInput   = document.getElementById('filterEndDate');
  const rows           = Array.from(document.querySelectorAll('.prod-row'));
  const detailRows     = Array.from(document.querySelectorAll('.detail-row'));
  const totalRevElem   = document.getElementById('totalRevenue');
  const activeTags     = document.getElementById('activeFilters');

  // Toggle detail rows
  document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.classList.toggle('open');
      const det = btn.closest('tr').nextElementSibling;
      det.style.display = det.style.display === 'none' ? '' : 'none';
    });
  });

  function updateActiveFilters(recipes) {
    activeTags.innerHTML = '';
    recipes.forEach(r => {
      const span = document.createElement('span');
      span.className = 'filter-chip';
      span.textContent = r;
      activeTags.appendChild(span);
    });
  }

  function filterTable() {
    const selRecipes  = recipeCBs.filter(cb => cb.checked).map(cb => cb.value);
    const selChefs    = chefCBs.filter(cb => cb.checked).map(cb => cb.value);
    const equipFilter = equipmentInput.value.trim().toLowerCase();
    const startDate   = startDateInput.value;
    const endDate     = endDateInput.value;

    updateActiveFilters(selRecipes);

    let grandTotal = 0;

    rows.forEach((row, i) => {
      const recs      = row.dataset.recipes;
      const chefs     = row.dataset.chefs;
      const equipment = row.dataset.equipment;
      const date      = row.dataset.date;

      const recipeMatch = !selRecipes.length || selRecipes.some(r => recs.includes(r));
      const chefMatch   = !selChefs.length   || selChefs.some(c => chefs.includes(c));
      const equipMatch  = !equipFilter       || equipment.includes(equipFilter);
      const dateMatch   = (!startDate || date >= startDate) && (!endDate || date <= endDate);

      const showRow = recipeMatch && chefMatch && equipMatch && dateMatch;

      row.style.display        = showRow ? '' : 'none';
      detailRows[i].style.display = showRow ? '' : 'none';

      if (!showRow) return;

      let rowSum = 0;
      const cell = row.querySelector('.row-potential');

      detailRows[i].querySelectorAll('li').forEach(li => {
        const recipe    = li.dataset.recipe;
        const potential = parseFloat(li.dataset.potential) || 0;

        if (!selRecipes.length || selRecipes.includes(recipe)) {
          li.style.display = '';
          rowSum += potential;
        } else {
          li.style.display = 'none';
        }
      });

      cell.textContent = `$${rowSum.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      })}`;

      grandTotal += rowSum;
    });

    totalRevElem.textContent = `$${grandTotal.toLocaleString(undefined, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })}`;
  }

  // Wire up all filters
  [...recipeCBs, ...chefCBs].forEach(cb => cb.addEventListener('change', filterTable));
  equipmentInput.addEventListener('input', filterTable);
  startDateInput.addEventListener('change', filterTable);
  endDateInput.addEventListener('change', filterTable);

  // Initial run
  filterTable();
});
</script>
@endsection
