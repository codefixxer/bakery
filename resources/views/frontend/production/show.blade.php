{{-- resources/views/frontend/production/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Production: ' . $production->production_date)

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header -->
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-gear-fill fs-2 me-3"></i>
      <h4 class="mb-0">Production Record: {{ $production->production_date }}</h4>
    </div>
    <div class="card-body">
      <!-- Summary -->
      <div class="row mb-4">
        <div class="col-md-4">
          <h6 class="text-uppercase text-muted small">Items Produced</h6>
          <p class="fs-4 fw-bold mb-0">{{ $production->details->count() }}</p>
        </div>
        <div class="col-md-4">
          <h6 class="text-uppercase text-muted small">Total Potential (€)</h6>
          <p class="fs-4 fw-bold mb-0">€{{ number_format($production->total_potential_revenue, 2) }}</p>
        </div>
        <div class="col-md-4 text-end">
          <a href="{{ route('production.edit', $production) }}" class="btn btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
          </a>
          <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
          </a>
          <form action="{{ route('production.destroy', $production) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">
              <i class="bi bi-trash me-1"></i>Delete
            </button>
          </form>
        </div>
      </div>

      <hr>

      <!-- Details Table -->
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Recipe</th>
              <th>Chef</th>
              <th class="text-end">Qty</th>
              <th class="text-end">Exec Time (m)</th>
              <th>Equipment</th>
              <th class="text-end">Potential (€)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($production->details as $detail)
              @php
                $ids   = is_array($detail->equipment_ids)
                          ? $detail->equipment_ids
                          : (strlen($detail->equipment_ids)
                             ? explode(',', $detail->equipment_ids)
                             : []);
                $names = array_map(fn($id) => $equipmentMap[$id] ?? $id, $ids);
                $equip = $names ? implode(', ', $names) : '—';
              @endphp
              <tr>
                <td>{{ $detail->recipe->recipe_name }}</td>
                <td>{{ $detail->chef->name }}</td>
                <td class="text-end">{{ $detail->quantity }}</td>
                <td class="text-end">{{ $detail->execution_time }}</td>
                <td>{{ $equip }}</td>
                <td class="text-end">€{{ number_format($detail->potential_revenue, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
