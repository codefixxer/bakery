{{-- resources/views/frontend/recipe/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Recipe: ' . $recipe->recipe_name)

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header with icon and recipe name -->
    <div class="card-header d-flex align-items-center" style="background-color: #041930; color: #e2ae76;">
      <!-- Recipe Icon -->
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
           style="width: 30px; height: 30px; margin-right: 8px; fill: #e2ae76;">
        <path d="M356.334,494.134c43.124-12.321,153.745-52.878,155.636-110.035c1.88-57.184-85.049-58.301-139.549-49.294L356.334,494.134z"/>
        <path d="M17.864,155.664l159.328-16.088c9.01-54.497,7.893-141.426-49.291-139.546C70.742,1.918,30.184,112.54,17.864,155.664z"/>
        <path d="M182.525,479.291c17.563,9.501,39.263,18.014,58.835,23.244c44.236,11.799,107.683,14.791,113.83-4.066c6.165-18.838,22.757-161.567,15.537-175.497c-7.204-13.913-32.605-22.372-47.628-26.378c-5.971-1.59-13.743-3.393-21.822-4.467L182.525,479.291z"/>
        <path d="M9.466,270.641c5.227,19.569,13.741,41.27,23.244,58.835l187.165-118.752c-1.076-8.081-2.879-15.851-4.47-21.824c-4.015-15.03-12.462-40.422-26.375-47.626c-13.93-7.219-156.661,9.37-175.497,15.537C-5.325,162.957-2.332,226.404,9.466,270.641z"/>
        <path d="M277.509,234.492c-10.833-10.833-30.659-28.329-46.765-27.786C214.616,207.227,48.711,314.21,34.496,328.424c-14.223,14.223,18.794,66.572,50.651,98.429c31.855,31.855,84.205,64.874,98.429,50.651c14.215-14.215,121.194-180.123,121.717-196.251C305.836,265.147,288.341,245.322,277.509,234.492z"/>
      </svg>
      <h4 class="mb-0" style="font-size: 16px; color: #e2ae76;">
        Recipe: {{ $recipe->recipe_name }}
      </h4>
    </div>

    <div class="card-body">
      <!-- Key Metrics -->
      <div class="row row-cols-1 row-cols-md-2 g-4 mb-3" style="width: 50%">
        <div class="col">
          <p class="text-uppercase text-muted small mb-1" style="font-size: 14px;">Category</p>
          <p class="fs-5 fw-bold mb-0" style="font-size: 16px;">{{ $recipe->category->name ?? '—' }}</p>
        </div>
        <div class="col">
          <p class="text-uppercase text-muted small mb-1" style="font-size: 14px;">Department</p>
          <p class="fs-5 fw-bold mb-0" style="font-size: 16px;">{{ $recipe->department->name ?? '—' }}</p>
        </div>
        <div class="col">
          <p class="text-uppercase text-muted small mb-1" style="font-size: 14px;">Sell Mode</p>
          <p class="fs-5 fw-bold mb-0 text-uppercase" style="font-size: 16px;">{{ $recipe->sell_mode }}</p>
        </div>
        <div class="col">
          @php
            $sell = $recipe->sell_mode === 'piece'
                    ? $recipe->selling_price_per_piece
                    : $recipe->selling_price_per_kg;
          @endphp
          <p class="text-uppercase text-muted small mb-1" style="font-size: 14px;">Price (€)</p>
          <p class="fs-5 fw-bold mb-0" style="font-size: 16px;">€{{ number_format($sell, 2) }}</p>
        </div>
        <div class="col">
          <p class="text-uppercase text-muted small mb-1" style="font-size: 14px;">Ingredients Cost (€)</p>
          <p class="fs-5 fw-bold mb-0" style="font-size: 16px;">€{{ number_format($recipe->ingredients_total_cost, 2) }}</p>
        </div>
        <div class="col">
          <p class="text-uppercase text-muted small mb-1" style="font-size: 14px;">Labour Cost (€)</p>
          <p class="fs-5 fw-bold mb-0" style="font-size: 16px;">€{{ number_format($recipe->labour_cost, 2) }}</p>
        </div>
        <div class="col">
          @php
            $totalCost = $recipe->ingredients_total_cost + $recipe->labour_cost;
          @endphp
          <p class="text-uppercase text-muted small mb-1" style="font-size: 14px;">Total Cost (€)</p>
          <p class="fs-5 fw-bold mb-0" style="font-size: 16px;">€{{ number_format($totalCost, 2) }}</p>
        </div>
        <div class="col">
          @php
            $margin = $recipe->potential_margin;
            $pct    = $sell > 0 ? round($margin * 100 / $sell, 2) : 0;
          @endphp
          <p class="text-uppercase text-muted small mb-1" style="font-size: 14px;">Margin</p>
          <p class="fs-5 fw-bold mb-0" style="font-size: 16px;">
            @if($margin >= 0)
              <span class="text-success">€{{ number_format($margin, 2) }} ({{ $pct }}%)</span>
            @else
              <span class="text-danger">€{{ number_format($margin, 2) }} ({{ $pct }}%)</span>
            @endif
          </p>
        </div>
      </div>

      <hr class="border-secondary">

      <!-- Ingredients Breakdown Table -->
      <h5 class="mt-4" style="font-size: 16px;">Ingredients Breakdown</h5>
      <div class="table-responsive">
        <table class="table table-striped table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th style="font-size: 14px;">Ingredient</th>
              <th style="font-size: 14px;" class="text-end">Qty (g)</th>
              <th style="font-size: 14px;" class="text-end">Cost (€)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recipe->ingredients as $ing)
              <tr>
                <td style="font-size: 14px;">{{ $ing->ingredient->ingredient_name }}</td>
                <td style="font-size: 14px;" class="text-end">{{ $ing->quantity_g }}</td>
                <td style="font-size: 14px;" class="text-end">€{{ number_format($ing->cost, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Actions -->
      <div class="mt-4 text-end">
        <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-gold me-2">
          <i class="bi bi-pencil me-1"></i> Edit
        </a>
        <a href="{{ route('recipes.index') }}" class="btn btn-deepblue me-2">
          <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
        <form action="{{ route('recipes.destroy', $recipe->id) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Delete this recipe?');">
          @csrf
          @method('DELETE')
          <button class="btn btn-red" type="submit">
            <i class="bi bi-trash me-1"></i> Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

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
    border: 1px solid #ff0000 !important;
    color: #ff0000 !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: #ff0000 !important;
    color: white !important;
  }

  .btn-gold i,
  .btn-deepblue i,
  .btn-red i {
    color: inherit !important;
  }
</style>
