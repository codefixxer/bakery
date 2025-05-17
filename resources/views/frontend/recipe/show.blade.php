{{-- resources/views/frontend/recipe/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Recipe: '.$recipe->recipe_name)

@section('content')
<div class="container py-5">
  <div class="card shadow-lg">
    <div class="card-header bg-dark text-gold d-flex align-items-center">
      <h5 class="mb-0">Recipe: {{ $recipe->recipe_name }}</h5>
    </div>

    <div class="card-body">
      {{-- Key Metrics --}}
      @php
        // 1) selling price
        $sellPrice = $recipe->sell_mode === 'piece'
                    ? $recipe->selling_price_per_piece
                    : $recipe->selling_price_per_kg;

        // 2) dynamic batch costs
        $ingBatch   = $recipe->ingredients_cost_per_batch;
        $labBatch   = $recipe->labor_cost;
        $totalBatch = $ingBatch + $labBatch;

        // 3) per-unit cost (by piece or by kg)
        if($recipe->sell_mode==='piece'){
          $units    = max($recipe->total_pieces,1);
          $unitCost = $totalBatch/$units;
        } else {
          $grams    = max($recipe->recipe_weight, $recipe->ingredients->sum('quantity_g'));
          $kg       = $grams/1000;
          $unitCost = $totalBatch/max($kg,1);
        }

        // 4) margin
        $margin = $sellPrice - $unitCost;
        $pct    = $sellPrice>0 ? round($margin*100/$sellPrice,2) : 0;
      @endphp

      <div class="row mb-4">
        <div class="col-md-3">
          <strong>Price (€)</strong>
          <div>€{{ number_format($sellPrice,2) }}</div>
        </div>
        <div class="col-md-3">
          <strong>Ingredients Cost (€)</strong>
          <div>€{{ number_format($ingBatch,2) }}</div>
        </div>
        <div class="col-md-3">
          <strong>Labor Cost (€)</strong>
          <div>€{{ number_format($labBatch,2) }}</div>
        </div>
        <div class="col-md-3">
          <strong>Total Cost (€)</strong>
          <div>€{{ number_format($totalBatch,2) }}</div>
        </div>
        <div class="col-md-3 mt-3">
          <strong>Margin per {{ $recipe->sell_mode }}</strong>
          <div @class([
             'text-success'=>$margin>=0,
             'text-danger'=>$margin<0
           ])>
            €{{ number_format($margin,2) }} ({{ $pct }}%)
          </div>
        </div>
      </div>

      <hr>

      {{-- Ingredients Breakdown --}}
      <h6>Ingredients Breakdown</h6>
      <div class="table-responsive">
        <table class="table table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>Ingredient</th>
              <th class="text-end">Qty (g)</th>
              <th class="text-end">Cost (€)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recipe->ingredients as $ri)
              @php
                // dynamic per-ingredient cost
                $cost = round(($ri->quantity_g/1000)*$ri->ingredient->price_per_kg,2);
              @endphp
              <tr>
                <td>{{ $ri->ingredient->ingredient_name }}</td>
                <td class="text-end">{{ $ri->quantity_g }}</td>
                <td class="text-end">€{{ number_format($cost,2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Actions --}}
      <div class="mt-4 text-end">
        <a href="{{ route('recipes.edit',$recipe->id) }}" class="btn btn-outline-gold">Edit</a>
        <a href="{{ route('recipes.index') }}" class="btn btn-outline-blue">Back</a>
      </div>

    </div>
  </div>
</div>




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
@endsection
