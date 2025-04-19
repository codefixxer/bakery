{{-- resources/views/frontend/recipe/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','All Recipes')

@section('content')
<div class="container py-5">

  {{-- 1) Search / Filter Card --}}
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <div class="row g-3 align-items-end">
        {{-- Recipe Name --}}
        <div class="col-6 col-md-2">
          <label for="searchName" class="form-label small mb-1">Name</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" id="searchName" class="form-control" placeholder="Recipe name…">
          </div>
        </div>

        <div class="col-6 col-md-2">
          <label for="filterDept" class="form-label small mb-1">Department</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-building"></i></span>
            <select id="filterDept" class="form-select">
              <option value="">All depts</option>
              @foreach($departments as $dept)
                <option value="{{ strtolower($dept->name) }}">{{ $dept->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Sell Mode --}}
        <div class="col-6 col-md-2">
          <label for="filterMode" class="form-label small mb-1">Mode</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-filter"></i></span>
            <select id="filterMode" class="form-select">
              <option value="">All modes</option>
              <option value="piece">Piece</option>
              <option value="kg">Kg</option>
            </select>
          </div>
        </div>

        {{-- Ingredient --}}
          <div class="col-6 col-md-2">
            <label for="searchIngredient" class="form-label small mb-1">Ingredient</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-list"></i></span>
              <input type="text" id="searchIngredient" class="form-control" placeholder="Ingredient…">
            </div>
          </div>

        {{-- From Date --}}
        <div class="col-6 col-md-3">
          <label for="filterStartDate" class="form-label small mb-1">From Date</label>
          <input type="date" id="filterStartDate" class="form-control">
        </div>

        {{-- To Date --}}
        <div class="col-6 col-md-3">
          <label for="filterEndDate" class="form-label small mb-1">To Date</label>
          <input type="date" id="filterEndDate" class="form-control">
        </div>
      </div>
    </div>
  </div>

  {{-- 2) Recipe Cards --}}
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 g-4 mt-3" id="recipesContainer">
    @foreach($recipes as $r)
      @php
        $ingNames = $r->ingredients->pluck('ingredient.ingredient_name')->implode(',');
      @endphp
      <div class="col recipe-card"
           data-name="{{ strtolower($r->recipe_name) }}"
           data-mode="{{ $r->sell_mode }}"
           data-ingredients="{{ strtolower($ingNames) }}"
           data-created="{{ $r->created_at->format('Y-m-d') }}">
        <div class="card h-100 shadow-sm">

          {{-- header --}}
         {{-- header --}}
{{-- resources/views/frontend/recipe/index.blade.php --}}
{{-- … inside your @foreach($recipes as $r) … --}}
<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
  <div>
    <h5 class="mb-0">{{ $r->recipe_name }}</h5>
    <div class="mt-1">
      <span class="badge bg-info me-1 text-uppercase">
        {{ $r->category->name ?? 'Uncategorized' }}
      </span>
      <span class="badge bg-success text-uppercase">
        {{ $r->department->name ?? 'No Dept' }}
      </span>
    </div>
  </div>
  <span class="badge bg-light text-primary text-uppercase">{{ $r->sell_mode }}</span>
</div>



          {{-- body --}}
          <div class="card-body">
            <dl class="row small mb-3">
              @if($r->sell_mode === 'piece')
                <dt class="col-6">Price/piece</dt>
                <dd class="col-6 text-end">${{ number_format($r->selling_price_per_piece,2) }}</dd>
              @else
                <dt class="col-6">Price/kg</dt>
                <dd class="col-6 text-end">${{ number_format($r->selling_price_per_kg,2) }}</dd>
              @endif

              <dt class="col-6">Labour (min)</dt>
              <dd class="col-6 text-end">{{ $r->labour_time_min }}</dd>

              <dt class="col-6">Labour cost</dt>
              <dd class="col-6 text-end">${{ number_format($r->labour_cost,2) }}</dd>

              <dt class="col-6">Ingr. cost</dt>
              <dd class="col-6 text-end">${{ number_format($r->ingredients_total_cost,2) }}</dd>

              <dt class="col-6">Total exp</dt>
              <dd class="col-6 text-end">${{ number_format($r->total_expense,2) }}</dd>

              <dt class="col-6">Margin</dt>
              <dd class="col-6 text-end">
                @if($r->potential_margin >= 0)
                  <span class="text-success">${{ number_format($r->potential_margin,2) }}</span>
                @else
                  <span class="text-danger">${{ number_format($r->potential_margin,2) }}</span>
                @endif
              </dd>
            </dl>

            <h6 class="fw-semibold">Ingredients</h6>
            <ul class="list-group list-group-flush small">
              @foreach($r->ingredients as $ri)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                  {{ $ri->ingredient->ingredient_name }} ({{ $ri->quantity_g }}g)
                  <span class="badge bg-secondary">${{ number_format($ri->cost,2) }}</span>
                </li>
              @endforeach
            </ul>
          </div>

          {{-- footer --}}
          <div class="card-footer text-muted small d-flex justify-content-between align-items-center">
            <div>
              <span>Created: {{ $r->created_at->format('Y-m-d') }}</span><br>
              <span>Updated: {{ $r->updated_at->format('Y-m-d') }}</span>
            </div>
            <div class="btn-group btn-group-sm">
              <a href="{{ route('recipes.edit', $r->id) }}" class="btn btn-outline-primary" title="Edit">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="{{ route('recipes.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Delete this recipe?');">
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
  const nameInput      = document.getElementById('searchName');
  const modeSelect     = document.getElementById('filterMode');
  const ingInput       = document.getElementById('searchIngredient');
  const startDateInput = document.getElementById('filterStartDate');
  const endDateInput   = document.getElementById('filterEndDate');
  const cards          = document.querySelectorAll('.recipe-card');

  function filterRecipes() {
    const nameVal  = nameInput.value.trim().toLowerCase();
    const modeVal  = modeSelect.value;
    const ingVal   = ingInput.value.trim().toLowerCase();
    const startVal = startDateInput.value;
    const endVal   = endDateInput.value;

    cards.forEach(card => {
      const name        = card.dataset.name;
      const mode        = card.dataset.mode;
      const ingredients = card.dataset.ingredients;
      const created     = card.dataset.created;

      const matchName = !nameVal  || name.includes(nameVal);
      const matchMode = !modeVal  || mode === modeVal;
      const matchIng  = !ingVal   || ingredients.includes(ingVal);

      let matchDate = true;
      if (startVal) matchDate = matchDate && (created >= startVal);
      if (endVal)   matchDate = matchDate && (created <= endVal);

      card.style.display = (matchName && matchMode && matchIng && matchDate) ? '' : 'none';
    });
  }

  // never allow To < From
  startDateInput.addEventListener('change', () => {
    endDateInput.min = startDateInput.value;
    if (endDateInput.value && endDateInput.value < startDateInput.value) {
      endDateInput.value = startDateInput.value;
    }
    filterRecipes();
  });
  endDateInput.addEventListener('change', filterRecipes);
  nameInput.addEventListener('input', filterRecipes);
  modeSelect.addEventListener('change', filterRecipes);
  ingInput.addEventListener('input', filterRecipes);
});
</script>
@endsection
