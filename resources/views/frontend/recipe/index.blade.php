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
              <th>Name</th>
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
                // 1) unit selling price
                $unitSell = $r->sell_mode === 'piece'
                              ? $r->selling_price_per_piece
                              : $r->selling_price_per_kg;

                // 2) total batch costs
                $batchIngCost = $r->ingredients_total_cost;
                $batchLabCost = $r->labour_cost;

                // 3) compute per-unit spread
                if ($r->sell_mode === 'piece') {
                  $pieces       = $r->total_pieces ?: 1;
                  $unitIngCost  = $batchIngCost / $pieces;
                  $unitLabCost  = $batchLabCost / $pieces;
                } else {
                  $wLoss        = $r->recipe_weight
                                  ?: ($r->ingredients->sum(fn($i) => $i->quantity_g));
                  $kg           = ($wLoss / 1000) ?: 1;
                  $unitIngCost  = $batchIngCost / $kg;
                  $unitLabCost  = $batchLabCost / $kg;
                }

                // 4) totals & margin
                $unitTotalCost = $unitIngCost + $unitLabCost;
                $unitMargin    = $unitSell - $unitTotalCost;

                // 5) percentages vs unit sell
                $ingPct  = $unitSell>0 ? round($unitIngCost*100/$unitSell,2) : 0;
                $labPct  = $unitSell>0 ? round($unitLabCost*100/$unitSell,2) : 0;
                $costPct = $unitSell>0 ? round($unitTotalCost*100/$unitSell,2): 0;
                $marPct  = $unitSell>0 ? round($unitMargin*100/$unitSell,2)  : 0;

                $ingredientsData = $r->ingredients->map(fn($i)=>[
                  'name' =>$i->ingredient->ingredient_name,
                  'qty_g'=>$i->quantity_g,
                  'cost' =>$i->cost,
                ]);
              @endphp

              <tr class="dt-control" data-ingredients='@json($ingredientsData)'>
                <td>{{ $r->recipe_name }}</td>
                <td>
                  <span class="badge bg-secondary text-uppercase">{{ $r->sell_mode }}</span>
                </td>

           {{-- PRICE --}}
{{-- PRICE --}}
<td class="text-end" data-order="{{ $unitSell }}">
  <div class="d-flex flex-column align-items-end">
    <span>€{{ number_format($unitSell,2) }}</span>
    <small class="text-muted">(100%)</small>
  </div>
</td>



     {{-- INGREDIENT COST --}}
<td class="text-end" data-order="{{ $unitIngCost }}">
  <div class="d-flex flex-column align-items-end">
    <span>€{{ number_format($unitIngCost,2) }}</span>
    <small class="text-muted">({{ $ingPct }}%)</small>
  </div>
</td>

{{-- LABOR COST --}}
<td class="text-end" data-order="{{ $unitLabCost }}">
  <div class="d-flex flex-column align-items-end">
    <span>€{{ number_format($unitLabCost,2) }}</span>
    <small class="text-muted">({{ $labPct }}%)</small>
  </div>
</td>

{{-- TOTAL COST --}}
<td class="text-end" data-order="{{ $unitTotalCost }}">
  <div class="d-flex flex-column align-items-end">
    <span>€{{ number_format($unitTotalCost,2) }}</span>
    <small class="text-muted">({{ $costPct }}%)</small>
  </div>
</td>

{{-- MARGIN --}}
<td class="text-end" data-order="{{ $unitMargin }}">
  <div class="d-flex flex-column align-items-end">
    @if($unitMargin >= 0)
      <span class="text-success">€{{ number_format($unitMargin,2) }}</span>
    @else
      <span class="text-danger">€{{ number_format($unitMargin,2) }}</span>
    @endif
    <small class="text-muted">({{ $marPct }}%)</small>
  </div>
</td>


                {{-- Actions --}}
               <td class="text-center">
  <a
    href="{{ route('recipes.edit', $r->id) }}"
    class="btn btn-sm me-1"
    style="
      border:1px solid #e2ae76;
      color:#e2ae76;
      background-color:transparent;
      transition: background-color .2s, color .2s;
    "
    onmouseover="this.style.backgroundColor='#e2ae76'; this.style.color='#fff';"
    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#e2ae76';"
  >
    <i class="bi bi-pencil"></i>
  </a>

  <a
    href="{{ route('recipes.show', $r->id) }}"
    class="btn btn-sm me-1"
    style="
      border:1px solid #041930;
      color:#041930;
      background-color:transparent;
      transition: background-color .2s, color .2s;
    "
    onmouseover="this.style.backgroundColor='#041930'; this.style.color='#fff';"
    onmouseout="this.style.backgroundColor='transparent'; this.style.color='#041930';"
  >
    <i class="bi bi-eye"></i>
  </a>

  <form
    action="{{ route('recipes.destroy', $r->id) }}"
    method="POST"
    class="d-inline"
    onsubmit="return confirm('Delete?')"
  >
    @csrf
    @method('DELETE')
    <button
      type="submit"
      class="btn btn-sm"
      style="
        border:1px solid #ff0000;
        color:#ff0000;
        background-color:transparent;
        transition: background-color .2s, color .2s;
      "
      onmouseover="this.style.backgroundColor='#ff0000'; this.style.color='#fff';"
      onmouseout="this.style.backgroundColor='transparent'; this.style.color='#ff0000';"
    >
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
  table#recipesTable thead.custom-recipe-head th {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    text-align: center;
  }
  /* … your existing column‐width, button & DataTable overrides … */
</style>

@section('scripts')
<script>
  $(function(){
    $('#recipesTable').DataTable({
      paging: true,
      ordering: true,
      responsive: true,
      pageLength: 10,
      order: [[1,'asc']],
      columnDefs: [
        { orderable:false, targets:0 },
        { orderable:false, targets:-1 }
      ]
    })
    .on('click','td.dt-control', function(){
      const tr  = $(this).closest('tr'),
            row = $('#recipesTable').DataTable().row(tr);
      if(row.child.isShown()){
        row.child.hide(); tr.removeClass('shown');
      } else {
        const data = JSON.parse(tr.attr('data-ingredients')),
              html = '<table class="table mb-0"><thead><tr>'+
                     '<th>Ingredient</th>'+
                     '<th class="text-end">Qty (g)</th>'+
                     '<th class="text-end">Cost</th>'+
                     '</tr></thead><tbody>'+
                     data.map(i=>`<tr>
                        <td>${i.name}</td>
                        <td class="text-end">${i.qty_g}</td>
                        <td class="text-end">€${parseFloat(i.cost).toFixed(2)}</td>
                      </tr>`).join('')+
                     '</tbody></table>';
        row.child(html).show(); tr.addClass('shown');
      }
    });
  });
</script>
@endsection
