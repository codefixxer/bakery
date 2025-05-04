{{-- resources/views/frontend/recipe/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Recipes')

@section('content')
<div class="container py-5">
  <!-- Header -->
  <div class="card mb-4 shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">All Recipes</h5>
      <small>Quickly search, sort, and filter all your recipes below.</small>
    </div>
  </div>

  <!-- Table -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table
          id="recipesTable"
          class="table table-striped table-hover table-bordered mb-0"
          style="width:100%;"
        >
          <thead class="table-primary">
            <tr>
              <th style="width:1%"></th>
              <th>Created By</th>

              <th>Name</th>
              <th>Category</th>
              <th>Department</th>
              <th>Sell Mode</th>
              <th class="text-end">Price</th>
              <th class="text-end">Ing. Cost</th>
              <th class="text-end">Lab. Cost</th>
              <th class="text-end">Total Cost</th>
              <th class="text-end">Margin</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>

          <tbody>
            @foreach($recipes as $r)
              @php
                $sell = $r->sell_mode === 'piece'
                        ? $r->selling_price_per_piece
                        : $r->selling_price_per_kg;
                $ingCost    = $r->ingredients_total_cost;
                $labCost    = $r->labour_cost;
                $totalCosts = $ingCost + $labCost;
                $marVal     = $r->potential_margin;
                $ingInc   = $sell > 0 ? round($ingCost * 100 / $sell, 2) : 0;
                $labInc   = $sell > 0 ? round($labCost * 100 / $sell, 2) : 0;
                $costInc  = $sell > 0 ? round($totalCosts * 100 / $sell, 2) : 0;
                $marPct   = $sell > 0 ? round($marVal * 100 / $sell, 2) : 0;
              @endphp

<tr class="dt-control" data-ingredients='@json(
  $r->ingredients->map(fn($ing) => [
    'name'   => $ing->ingredient?->ingredient_name ?? 'Unknown',
    'qty_g'  => $ing->quantity_g,
    'cost'   => $ing->cost
  ])
)'>

<td></td>
<td>
  <span class="badge bg-light text-dark">{{ $r->user->name ?? '—' }}</span>
</td>
                <td>{{ $r->recipe_name }}</td>
                <td>{{ $r->category->name ?? '—' }}</td>
                <td>{{ $r->department->name ?? '—' }}</td>
                <td><span class="badge bg-secondary text-uppercase">{{ $r->sell_mode }}</span></td>
                <td class="text-end">€{{ number_format($sell, 2) }}</td>
                <td class="text-end">€{{ number_format($ingCost, 2) }} <small class="text-muted">({{ $ingInc }}%)</small></td>
                <td class="text-end">€{{ number_format($labCost, 2) }} <small class="text-muted">({{ $labInc }}%)</small></td>
                <td class="text-end">€{{ number_format($totalCosts, 2) }} <small class="text-muted">({{ $costInc }}%)</small></td>
                <td class="text-end">
                  @if($marVal >= 0)
                    <span class="text-success">€{{ number_format($marVal, 2) }} <small>({{ $marPct }}%)</small></span>
                  @else
                    <span class="text-danger">€{{ number_format($marVal, 2) }} <small>({{ $marPct }}%)</small></span>
                  @endif
                </td>
                <td class="text-center">
                  <a href="{{ route('recipes.edit', $r->id) }}"
                     class="btn btn-sm btn-outline-light border-0 bg-primary text-white me-1"
                     title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="{{ route('recipes.show', $r->id) }}"
                     class="btn btn-sm btn-outline-light border-0 bg-info text-white me-1"
                     title="View">
                    <i class="bi bi-eye"></i>
                  </a>
                  <form action="{{ route('recipes.destroy', $r->id) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Delete this recipe?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-light border-0 bg-danger text-white" title="Delete">
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
  $(function(){
    const table = $('#recipesTable').DataTable({
      paging: true,
      ordering: true,
      responsive: true,
      pageLength: 10,
      order: [[1, 'asc']],
      columnDefs: [{ orderable: false, targets: 0 }]
    });

    $('#recipesTable tbody').on('click', 'td.dt-control', function(){
      const tr  = $(this).closest('tr');
      const row = table.row(tr);
      if(row.child.isShown()){
        row.child.hide();
        tr.removeClass('shown');
      } else {
        const data = tr.data('ingredients');
        let html = '<table class="table mb-0"><thead><tr>' +
                   '<th>Ingredient</th><th class="text-end">Qty (g)</th><th class="text-end">Cost</th>' +
                   '</tr></thead><tbody>';
        data.forEach(i => {
          html += `<tr>
                     <td>${i.name}</td>
                     <td class="text-end">${i.qty_g}</td>
                     <td class="text-end">€${parseFloat(i.cost).toFixed(2)}</td>
                   </tr>`;
        });
        html += '</tbody></table>';
        row.child(html).show();
        tr.addClass('shown');
      }
    });
  });
</script>
@endsection
