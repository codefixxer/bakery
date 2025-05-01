@extends('frontend.layouts.app')

@section('title','All Recipes')

@section('content')
<div class="container py-5">
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Recipes</h5>
      <p class="card-text">Quickly search, sort, and filter all your recipes below.</p>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table
          id="recipesTable"
          class="table table-striped table-hover table-bordered mb-0"
          style="width:100%;"
        >
        <thead class="table-light">
          <tr>
            <th style="width:1%"></th>          {{-- ← control column --}}
            <th>Name</th>
            <th>Category</th>
            <!-- …etc… -->
            <th>Actions</th>
          </tr>
        </thead>
        
          <tbody>
            @foreach($recipes as $r)
              @php
                $sell = $r->sell_mode === 'piece'
                  ? $r->selling_price_per_piece
                  : $r->selling_price_per_kg;

                $ingCost = $r->ingredients_total_cost;
                $labCost = $r->labour_cost;
                $totalCosts = $ingCost + $labCost;

                $ingInc = $sell > 0
                  ? round($ingCost * 100 / $sell, 2)
                  : 0;
                $labInc = $sell > 0
                  ? round($labCost * 100 / $sell, 2)
                  : 0;
                $costInc = $sell > 0
                  ? round($totalCosts * 100 / $sell, 2)
                  : 0;

                $marVal = $r->potential_margin;
                $marPct = $sell > 0
                  ? round($marVal * 100 / $sell, 2)
                  : 0;
              @endphp
     <tr
     data-ingredients='@json($r->ingredients->map(fn($i)=>[
       "name"=>$i->name,
       "qty"=>$i->pivot->quantity,
       "unit"=>$i->unit
     ]))'
   >
     <td class="dt-control"></td>
     <td>{{ $r->recipe_name }}</td>
                <td>{{ $r->category->name ?? '—' }}</td>
                <td>{{ $r->department->name ?? '—' }}</td>
                <td>{{ strtoupper($r->sell_mode) }}</td>
                <td class="text-end">€{{ number_format($sell,2) }}</td>
                <td class="text-end">
                  €{{ number_format($ingCost,2) }}
                  <small class="text-muted">({{ $ingInc }}%)</small>
                </td>
                <td class="text-end">
                  €{{ number_format($labCost,2) }}
                  <small class="text-muted">({{ $labInc }}%)</small>
                </td>
                <td class="text-end">
                  €{{ number_format($totalCosts,2) }}
                  <small class="text-muted">({{ $costInc }}%)</small>
                </td>
                <td class="text-end">
                  @if($marVal >= 0)
                    <span class="text-success">
                      €{{ number_format($marVal,2) }}
                      <small>({{ $marPct }}%)</small>
                    </span>
                  @else
                    <span class="text-danger">
                      €{{ number_format($marVal,2) }}
                      <small>({{ $marPct }}%)</small>
                    </span>
                  @endif
                </td>
                <td class="text-center">
                  <a href="{{ route('recipes.edit', $r->id) }}"
                     class="btn btn-sm btn-outline-primary me-1"
                     title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('recipes.destroy', $r->id) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Delete this recipe?');">
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
  $(document).ready(function() {
    $('#recipesTable').DataTable({
      paging:      true,
      ordering:    true,
      order:       [[0, 'asc']],
      responsive:  true,
      pageLength:  10,
      lengthMenu:  [[10, 25, 50], [10, 25, 50]]
    });
  });
</script>
@endsection