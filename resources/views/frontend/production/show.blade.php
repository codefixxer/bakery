@extends('frontend.layouts.app')

@section('title', 'Production: ' . $production->production_date)

@section('content')
<div class="container py-5 px-md-4">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header -->
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-gear-fill fs-4 me-3" style="color: #e2ae76;"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">Production Record: {{ $production->production_date }}</h5>
    </div>

    <div class="card-body">
      <!-- Summary -->
      <div class="row mb-4 align-items-center">
        <div class="col-md-4">
          <h6 class="text-uppercase text-muted small">Items Produced</h6>
          <p class="fs-4 fw-bold mb-0">{{ $production->details->count() }}</p>
        </div>
        <div class="col-md-4">
          <h6 class="text-uppercase text-muted small">Total Potential (€)</h6>
          <p class="fs-4 fw-bold mb-0">€{{ number_format($production->total_potential_revenue, 2) }}</p>
        </div>
        <div class="col-md-4 text-end">
          <a href="{{ route('production.edit', $production) }}" class="btn btn-gold btn-sm me-1">
            <i class="bi bi-pencil me-1"></i>Edit
          </a>
          <a href="{{ route('production.index') }}" class="btn btn-deepblue btn-sm me-1">
            <i class="bi bi-arrow-left me-1"></i>Back
          </a>
          <form action="{{ route('production.destroy', $production) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-red btn-sm">
              <i class="bi bi-trash me-1"></i>Delete
            </button>
          </form>
        </div>
      </div>

      <!-- Details Table -->
      <div class="table-responsive">
        <table class="table table-bordered mb-0 align-middle text-center">
          <thead style="background-color: #e2ae76; color: #041930;">
            <tr>
              <th>Recipe</th>
              <th>Chef</th>
              <th>Qty</th>
              <th>Exec Time (m)</th>
              <th>Equipment</th>
              <th>Potential (€)</th>
            </tr>
          </thead>
          <tbody>
            @php
              $totalQty = 0;
              $totalPotential = 0;
              $totalExecTime = 0;
            @endphp
            @foreach($production->details as $detail)
              @php
                $ids = is_array($detail->equipment_ids)
                        ? $detail->equipment_ids
                        : (strlen($detail->equipment_ids)
                            ? explode(',', $detail->equipment_ids)
                            : []);
          
                $names = collect($ids)
                          ->map(fn($id) => $equipmentMap[trim($id)] ?? null)
                          ->filter()
                          ->unique()
                          ->values();
          
                $equip = $names->implode(', ');
          
                $totalQty += $detail->quantity;
                $totalPotential += $detail->potential_revenue;
                $totalExecTime += $detail->execution_time;
              @endphp
              <tr>
                <td>{{ $detail->recipe->recipe_name }}</td>
                <td>{{ $detail->chef->name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->execution_time }}</td>
                <td>{{ $equip }}</td>
                <td>€{{ number_format($detail->potential_revenue, 2) }}</td>
              </tr>
            @endforeach
            <tr class="fw-bold">
              <td colspan="2" class="text-end">Total:</td>
              <td>{{ $totalQty }}</td>
              <td>{{ $totalExecTime }}</td>
              <td></td>
              <td>€{{ number_format($totalPotential, 2) }}</td>
            </tr>
          </tbody>
          
        </table>
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
    border: 1px solid red !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: red !important;
    color: white !important;
  }
</style>
