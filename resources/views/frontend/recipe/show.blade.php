{{-- resources/views/frontend/recipe/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', $recipe->recipe_name)

@section('content')
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">{{ $recipe->recipe_name }}</h5>
    </div>
    <div class="card-body">
      {{-- Recipe Details --}}
      <dl class="row">
        <dt class="col-sm-4 fw-semibold">Category:</dt>
        <dd class="col-sm-8">{{ $recipe->category->name ?? '—' }}</dd>

        <dt class="col-sm-4 fw-semibold">Department:</dt>
        <dd class="col-sm-8">{{ $recipe->department->name ?? '—' }}</dd>

        <dt class="col-sm-4 fw-semibold">Sell Mode:</dt>
        <dd class="col-sm-8 text-uppercase">{{ $recipe->sell_mode }}</dd>

        <dt class="col-sm-4 fw-semibold">Price:</dt>
        @php
          $sell = $recipe->sell_mode === 'piece'
                  ? $recipe->selling_price_per_piece
                  : $recipe->selling_price_per_kg;
        @endphp
        <dd class="col-sm-8">€{{ number_format($sell, 2) }}</dd>

        <dt class="col-sm-4 fw-semibold">Ingredients Cost:</dt>
        <dd class="col-sm-8">€{{ number_format($recipe->ingredients_total_cost, 2) }}</dd>

        <dt class="col-sm-4 fw-semibold">Labour Cost:</dt>
        <dd class="col-sm-8">€{{ number_format($recipe->labour_cost, 2) }}</dd>

        <dt class="col-sm-4 fw-semibold">Total Cost:</dt>
        @php
          $total = $recipe->ingredients_total_cost + $recipe->labour_cost;
        @endphp
        <dd class="col-sm-8">€{{ number_format($total, 2) }}</dd>

        <dt class="col-sm-4 fw-semibold">Margin:</dt>
        @php
          $margin = $recipe->potential_margin;
          $pct    = $sell > 0 ? round($margin * 100 / $sell, 2) : 0;
        @endphp
        <dd class="col-sm-8">
          @if($margin >= 0)
            <span class="text-success">€{{ number_format($margin, 2) }} ({{ $pct }}%)</span>
          @else
            <span class="text-danger">€{{ number_format($margin, 2) }} ({{ $pct }}%)</span>
          @endif
        </dd>
      </dl>

      {{-- Ingredients breakdown --}}
      <h6 class="mt-4">Ingredients</h6>
      <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>Ingredient</th>
              <th class="text-end">Qty (g)</th>
              <th class="text-end">Cost (€)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recipe->ingredients as $ing)
              <tr>
                <td>{{ $ing->ingredient->ingredient_name }}</td>
                <td class="text-end">{{ $ing->quantity_g }}</td>
                <td class="text-end">{{ number_format($ing->cost, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Actions --}}
      <div class="mt-4">
        <a href="{{ route('recipes.edit', $recipe->id) }}" class="btn btn-primary me-2">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('recipes.index') }}" class="btn btn-secondary me-2">
          <i class="bi bi-list me-1"></i>Back to List
        </a>
        <form action="{{ route('recipes.destroy', $recipe->id) }}"
              method="POST"
              class="d-inline"
              onsubmit="return confirm('Delete this recipe?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">
            <i class="bi bi-trash me-1"></i>Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
