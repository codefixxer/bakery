{{-- resources/views/frontend/recipes/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Recipes')

@section('content')
<div class="container py-5">

  <!-- Header -->
  <div class="page-header d-flex align-items-center mb-4 p-4 rounded" style="background-color: #041930;">
    <i class="bi bi-bookmark-star-fill me-3 fs-3" style="color: #e2ae76;"></i>
    <div>
      <h4 class="mb-0 fw-bold" style="color: #e2ae76;">All Recipes</h4>
      <small class="d-block text-light">Quickly search, sort, and filter all your recipes below.</small>
    </div>
  </div>

  <!-- Table -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="table-responsive">
        <table id="recipesTable" class="table table-striped table-hover table-bordered mb-0" style="width:100%;">
          <thead class="custom-recipe-head">
            <tr class="text-center">
              <th></th>
              <th>Name</th>
             >
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
                $sell       = $r->sell_mode === 'piece' ? $r->selling_price_per_piece : $r->selling_price_per_kg;
                $ingCost    = $r->ingredients_total_cost;
                $labCost    = $r->labour_cost;
                $totalCosts = $ingCost + $labCost;
                $marVal     = $r->potential_margin;
                $ingPct     = $sell > 0 ? round($ingCost * 100 / $sell, 2) : 0;
                $labPct     = $sell > 0 ? round($labCost * 100 / $sell, 2) : 0;
                $costPct    = $sell > 0 ? round($totalCosts * 100 / $sell, 2) : 0;
                $marPct     = $sell > 0 ? round($marVal * 100 / $sell, 2) : 0;
                $ingredientsData = $r->ingredients->map(fn($ing)=>[
                  'name'=>$ing->ingredient->ingredient_name,
                  'qty_g'=>$ing->quantity_g,
                  'cost'=>$ing->cost
                ]);
              @endphp

              <tr class="dt-control" data-ingredients='@json($ingredientsData)'>
                <td></td>
                <td>{{ $r->recipe_name }}</td>
           
                <td style="width:30px;"><span class="badge bg-secondary text-uppercase">{{ $r->sell_mode }}</span></td>

                <td class="text-end">
                  <div class="d-flex flex-column align-items-end">
                    <span>€{{ number_format($sell, 2) }}</span>
                    <small class="text-muted">(0%)</small>
                  </div>
                </td>

                <td class="text-end">
                  <div class="d-flex flex-column align-items-end">
                    <span>€{{ number_format($ingCost, 2) }}</span>
                    <small class="text-muted">({{ $ingPct }}%)</small>
                  </div>
                </td>

                <td class="text-end">
                  <div class="d-flex flex-column align-items-end">
                    <span>€{{ number_format($labCost, 2) }}</span>
                    <small class="text-muted">({{ $labPct }}%)</small>
                  </div>
                </td>

                <td class="text-end">
                  <div class="d-flex flex-column align-items-end">
                    <span>€{{ number_format($totalCosts, 2) }}</span>
                    <small class="text-muted">({{ $costPct }}%)</small>
                  </div>
                </td>

                <td class="text-end">
                  <div class="d-flex flex-column align-items-end">
                    @if($marVal >= 0)
                      <span class="text-success">€{{ number_format($marVal, 2) }}</span>
                    @else
                      <span class="text-danger">€{{ number_format($marVal, 2) }}</span>
                    @endif
                    <small class="text-muted">({{ $marPct }}%)</small>
                  </div>
                </td>

                <td class="text-center">
                  <a href="{{ route('recipes.edit', $r->id) }}"
                     class="btn btn-sm btn-gold me-1"
                     title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="{{ route('recipes.show', $r->id) }}"
                     class="btn btn-sm btn-deepblue me-1"
                     title="View">
                    <i class="bi bi-eye"></i>
                  </a>
                  <form action="{{ route('recipes.destroy', $r->id) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Delete this recipe?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-red" title="Delete">
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

<style>
/* Sell Mode is the 5th column in your table */
/* Sell Mode is the 5th column */
#recipesTable thead th:nth-child(5),
#recipesTable tbody td:nth-child(5) {
  width: 30px;
 
  overflow: hidden;
  text-overflow: ellipsis;
}


#recipesTable tbody td:nth-child(2) {
    font-size: 1.1rem;
    font-weight: 500;
  }

  /* Give Sell Mode column (4th column) a fixed min-width */
  #recipesTable thead th:nth-child(5),
  #recipesTable tbody td:nth-child(5) {
    min-width: 10px;
  }
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #e2ae76 !important;
    background-color: transparent !important;
  }
  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: #fff !important;
  }
  .btn-deepblue {
    border: 1px solid #041930 !important;
    color: #041930 !important;
    background-color: transparent !important;
  }
  .btn-deepblue:hover {
    background-color: #041930 !important;
    color: #fff !important;
  }
  .btn-red {
    border: 1px solid red !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: red !important;
    color: #fff !important;
  }

  table#recipesTable thead.custom-recipe-head th {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    text-align: center;
    font-weight: 600;
    vertical-align: middle;
  }
  table.dataTable thead th.sorting:after,
  table.dataTable thead th.sorting_asc:after,
  table.dataTable thead th.sorting_desc:after {
    color: #041930 !important;
    opacity: 1 !important;
  }
</style>

@section('scripts')
<script>
  $(function() {
    const table = $('#recipesTable').DataTable({
      paging: true,
      ordering: true,
      responsive: true,
      pageLength: 10,
      order: [[1, 'asc']],
      columnDefs: [
        { orderable: false, targets: 0 },
        { orderable: false, targets: -1 }
      ]
    });

    $('#recipesTable tbody').on('click', 'td.dt-control', function () {
      const tr = $(this).closest('tr');
      const row = table.row(tr);

      if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
      } else {
        const data = JSON.parse(tr.attr('data-ingredients'));
        let html = '<table class="table mb-0"><thead><tr>' +
                   '<th>Ingredient</th>' +
                   '<th class="text-end">Qty (g)</th>' +
                   '<th class="text-end">Cost</th>' +
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
