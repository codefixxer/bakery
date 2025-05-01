{{-- resources/views/frontend/production/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Production Records')

@section('styles')
<style>
  /* Filter card */
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
  .filter-chip {
    display: inline-block;
    background: #0d6efd;
    color: #fff;
    padding: .25em .5em;
    border-radius: .5rem;
    margin: .15em .15em 0 0;
    font-size: .875rem;
  }
  /* Revenue card */
  .revenue-card {
    background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
  }
  /* Table styling */
  #productionTable {
    border-radius: .5rem;
    overflow: hidden;
  }
  #productionTable thead {
    background: #f8f9fa;
  }
  #productionTable tbody tr:hover {
    background: rgba(13,110,253,.05);
  }
  #productionTable tbody tr.detail-row td {
    background: #fafafa;
  }
  .toggle-btn i {
    transition: transform .2s;
  }
  .toggle-btn.open i {
    transform: rotate(90deg);
  }
</style>
@endsection

@section('content')
@php
  $allRecipes = $productions
    ->flatMap(fn($p) => $p->details->pluck('recipe.recipe_name'))
    ->unique()->sort();
  $allChefs = $productions
    ->flatMap(fn($p) => $p->details->pluck('chef.name'))
    ->unique()->sort();
@endphp

<div class="container py-5">

  {{-- 1) Filters + Total --}}
  <div class="card mb-4 shadow-sm filter-card p-3">
    <div class="row g-3 align-items-end">
      {{-- Recipe --}}
      <div class="col-6 col-md-3">
        <label class="form-label small">Recipe</label>
        <div class="dropdown">
          <button class="btn btn-outline-primary w-100 text-start dropdown-toggle"
                  data-bs-toggle="dropdown">
            <i class="bi bi-journal-bookmark me-1"></i> Recipes
          </button>
          <div class="dropdown-menu p-3">
            @foreach($allRecipes as $r)
              @php $slug = Str::slug($r,'_') @endphp
              <div class="form-check mb-1">
                <input class="form-check-input recipe-checkbox" type="checkbox"
                       value="{{ strtolower($r) }}" id="recipe_{{ $slug }}">
                <label class="form-check-label" for="recipe_{{ $slug }}">{{ $r }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      {{-- Chef --}}
      <div class="col-6 col-md-3">
        <label class="form-label small">Chef</label>
        <div class="dropdown">
          <button class="btn btn-outline-success w-100 text-start dropdown-toggle"
                  data-bs-toggle="dropdown">
            <i class="bi bi-person-lines-fill me-1"></i> Chefs
          </button>
          <div class="dropdown-menu p-3">
            @foreach($allChefs as $c)
              @php $slug = Str::slug($c,'_') @endphp
              <div class="form-check mb-1">
                <input class="form-check-input chef-checkbox" type="checkbox"
                       value="{{ strtolower($c) }}" id="chef_{{ $slug }}">
                <label class="form-check-label" for="chef_{{ $slug }}">{{ $c }}</label>
              </div>
            @endforeach
          </div>
        </div>
      </div>
      {{-- Equipment --}}
      <div class="col-6 col-md-3">
        <label class="form-label small">Equipment</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-tools"></i></span>
          <input type="text" id="filterEquipment" class="form-control"
                 placeholder="Type to filter…">
        </div>
      </div>
      {{-- From Date --}}
      <div class="col-6 col-md-3">
        <label class="form-label small">From</label>
        <input type="date" id="filterStartDate" class="form-control">
      </div>
      {{-- To Date --}}
      <div class="col-6 col-md-3">
        <label class="form-label small">To</label>
        <input type="date" id="filterEndDate" class="form-control">
      </div>
    </div>

    {{-- Active filter chips --}}
    <div id="activeFilters" class="mt-3"></div>

    {{-- Total card --}}
    <div class="row mt-4">
      <div class="col-12">
        <div class="card revenue-card shadow-sm">
          <div class="card-body d-flex justify-content-between align-items-center">
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

  {{-- 2) Table --}}
  <div class="table-responsive">
    <table class="table table-striped" id="productionTable">
      <thead class="table-light">
        <tr>
          <th style="width:48px"></th>
          <th>Date</th>
          <th>Items</th>
          <th>Potential</th>
        </tr>
      </thead>
      <tbody>
        @foreach($productions as $i => $p)
          @php
            $equipmentList = $p->details
              ->flatMap(fn($d) => (array)$d->equipment_ids)
              ->unique()
              ->map(fn($id) => $equipmentMap[$id] ?? '')
              ->filter()
              ->implode(' ');
          @endphp
       <tr class="prod-row"
       data-recipes  ="{{ strtolower($p->details->pluck('recipe.recipe_name')->implode(' ')) }}"
       data-chefs    ="{{ strtolower($p->details->pluck('chef.name')->implode(' ')) }}"
       data-equipment="{{ strtolower($equipmentList) }}"
       data-date     ="{{ $p->production_date }}"
       data-potential="{{ $p->total_potential_revenue }}">
     <!-- toggle detail -->
     <td>
       <button class="btn btn-sm btn-outline-secondary toggle-btn">
         <i class="bi bi-caret-right-fill"></i>
       </button>
     </td>
   
     <!-- date -->
     <td>{{ $p->production_date }}</td>
   
     <!-- item count -->
     <td>{{ $p->details->count() }}</td>
   
     <!-- total potential -->
     <td>${{ number_format($p->total_potential_revenue, 2) }}</td>
   
     <!-- actions: edit & delete -->
     <td class="text-center">
       <!-- Edit button -->
       <a href="{{ route('production.edit', $p->id) }}"
          class="btn btn-sm btn-outline-primary"
          title="Edit">
         <i class="bi bi-pencil"></i>
       </a>
   
       <!-- Delete form -->
       <form action="{{ route('production.destroy', $p->id) }}"
             method="POST"
             class="d-inline"
             onsubmit="return confirm('Are you sure you want to delete this record?');">
         @csrf
         @method('DELETE')
         <button class="btn btn-sm btn-outline-danger" title="Delete">
           <i class="bi bi-trash"></i>
         </button>
       </form>
     </td>
   </tr>
   
          <tr class="detail-row" style="display:none">
            <td colspan="4" class="p-3">
              <ul class="mb-0">
                @foreach($p->details as $d)
                  @php
                    $ids       = is_array($d->equipment_ids)
                                ? $d->equipment_ids
                                : (strlen($d->equipment_ids)
                                   ? explode(',',$d->equipment_ids)
                                   : []);
                    $names     = array_map(fn($id) => $equipmentMap[$id] ?? $id, $ids);
                    $equipDisp = $names ? implode(', ',$names) : '—';
                  @endphp
                  <li class="mb-1"
                      data-recipe    ="{{ strtolower($d->recipe->recipe_name) }}"
                      data-chef      ="{{ strtolower($d->chef->name) }}"
                      data-potential ="{{ $d->potential_revenue }}">
                    <strong>{{ $d->recipe->recipe_name }}</strong> &times; {{ $d->quantity }}
                    — Chef: {{ $d->chef->name }}, {{ $d->execution_time }}m,
                    <span class="text-primary"><i class="bi bi-tools"></i> {{ $equipDisp }}</span>
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const recipeCBs  = document.querySelectorAll('.recipe-checkbox');
  const chefCBs    = document.querySelectorAll('.chef-checkbox');
  const equipIn    = document.getElementById('filterEquipment');
  const startIn    = document.getElementById('filterStartDate');
  const endIn      = document.getElementById('filterEndDate');
  const rows       = document.querySelectorAll('#productionTable .prod-row');
  const details    = document.querySelectorAll('#productionTable .detail-row');
  const totalRev   = document.getElementById('totalRevenue');
  const activeTags = document.getElementById('activeFilters');

  // toggle detail rows
  document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const tr   = btn.closest('tr');
      const det  = tr.nextElementSibling;
      btn.classList.toggle('open');
      det.style.display = det.style.display === 'none' ? '' : 'none';
    });
  });

  function updateActiveFilters(selR, selC, eqF, from, to) {
    activeTags.innerHTML = '';
    // recipes
    if (selR.length) selR.forEach(r => activeTags.insertAdjacentHTML('beforeend',
      `<span class="filter-chip">${r}</span>`));
    else activeTags.insertAdjacentHTML('beforeend', `<span class="filter-chip">All Recipes</span>`);
    // chefs
    if (selC.length) selC.forEach(c => activeTags.insertAdjacentHTML('beforeend',
      `<span class="filter-chip">${c}</span>`));
    else activeTags.insertAdjacentHTML('beforeend', `<span class="filter-chip">All Chefs</span>`);
    // equipment
    activeTags.insertAdjacentHTML('beforeend',
      `<span class="filter-chip">${eqF||'All Equipment'}</span>`);
    // dates
    activeTags.insertAdjacentHTML('beforeend',
      `<span class="filter-chip">${from||'Any'}→${to||'Any'}</span>`);
  }

  function filterTable() {
    const selR = [...recipeCBs].filter(cb => cb.checked).map(cb => cb.value);
    const selC = [...chefCBs]  .filter(cb => cb.checked).map(cb => cb.value);
    const eqF  = equipIn.value.trim().toLowerCase();
    const from = startIn.value, to = endIn.value;
    let sum    = 0;

    updateActiveFilters(selR, selC, eqF, from, to);

    rows.forEach((row,i) => {
      const recs = row.dataset.recipes;
      const chfs = row.dataset.chefs;
      const eqs  = (row.dataset.equipment||'').toLowerCase();
      const date = row.dataset.date;

      const okDate = (!from||date>=from) && (!to||date<=to);
      const okEq   = !eqF || eqs.includes(eqF);
      const okR    = !selR.length || selR.some(r => recs.includes(r));
      const okC    = !selC.length || selC.some(c => chfs.includes(c));
      const show   = okDate && okEq && okR && okC;

      row.style.display        = show ? '' : 'none';
      details[i].style.display = 'none';

      if (show) {
        // sum only matching detail-items
        details[i].querySelectorAll('li').forEach(li => {
          const r = li.dataset.recipe,
                c = li.dataset.chef,
                p = parseFloat(li.dataset.potential) || 0;
          if ((!selR.length||selR.includes(r)) && (!selC.length||selC.includes(c))) {
            sum += p;
          }
        });
      }
    });

    totalRev.textContent = `$${sum.toFixed(2)}`;
  }

  // wire up
  recipeCBs.forEach(cb => cb.addEventListener('change', filterTable));
  chefCBs  .forEach(cb => cb.addEventListener('change', filterTable));
  equipIn  .addEventListener('input',   filterTable);
  startIn  .addEventListener('change',  filterTable);
  endIn    .addEventListener('change',  filterTable);

  filterTable();
});
</script>
@endsection
