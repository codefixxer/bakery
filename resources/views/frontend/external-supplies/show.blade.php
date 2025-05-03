{{-- resources/views/frontend/external-supplies/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', ucfirst($externalSupply->type) . ' — ' . $externalSupply->client->name)

@section('content')
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">
        {{ ucfirst($externalSupply->type) }}
        — {{ $externalSupply->client->name }}
        on {{ $externalSupply->supply_date->format('Y-m-d') }}
      </h5>
    </div>
    <div class="card-body">
      {{-- Summary --}}
      <dl class="row">
        <dt class="col-sm-4 fw-semibold">Client:</dt>
        <dd class="col-sm-8">{{ $externalSupply->client->name }}</dd>

        <dt class="col-sm-4 fw-semibold">Date:</dt>
        <dd class="col-sm-8">{{ $externalSupply->supply_date->format('Y-m-d') }}</dd>

        <dt class="col-sm-4 fw-semibold">Type:</dt>
        <dd class="col-sm-8">{{ ucfirst($externalSupply->type) }}</dd>

        <dt class="col-sm-4 fw-semibold">Revenue:</dt>
        <dd class="col-sm-8">
          €{{ number_format($externalSupply->total_amount, 2) }}
        </dd>

        <dt class="col-sm-4 fw-semibold">Cost:</dt>
        @php
          $cost = $externalSupply->lines->sum(fn($line) =>
            ($line->recipe->production_cost_per_kg ?? 0) / 1000 * $line->qty
          );
        @endphp
        <dd class="col-sm-8">€{{ number_format($cost, 2) }}</dd>

        <dt class="col-sm-4 fw-semibold">Profit:</dt>
        @php $profit = $externalSupply->total_amount - $cost; @endphp
        <dd class="col-sm-8">
          <span class="{{ $profit >= 0 ? 'text-success' : 'text-danger' }}">
            €{{ number_format($profit, 2) }}
          </span>
        </dd>
      </dl>

      {{-- Lines --}}
      <h6 class="mt-4">Items</h6>
      <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>Recipe</th>
              <th class="text-end">Qty</th>
              <th class="text-end">Line Rev (€)</th>
              <th class="text-end">Line Cost (€)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($externalSupply->lines as $line)
              @php
                $lineRev  = ($externalSupply->type === 'supply' ? 1 : -1) * $line->total_amount;
                $lineCost = ($line->recipe->production_cost_per_kg ?? 0) / 1000 * $line->qty;
              @endphp
              <tr>
                <td>{{ $line->recipe->recipe_name ?? '—' }}</td>
                <td class="text-end">{{ $line->qty }}</td>
                <td class="text-end">€{{ number_format($lineRev, 2) }}</td>
                <td class="text-end">€{{ number_format($lineCost, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Actions --}}
      <div class="mt-4">
        <a href="{{ route('external-supplies.edit', $externalSupply) }}" class="btn btn-primary me-2">
          <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('external-supplies.index') }}" class="btn btn-secondary me-2">
          <i class="bi bi-list me-1"></i>Back to List
        </a>
        <form action="{{ route('external-supplies.destroy', $externalSupply) }}"
              method="POST" class="d-inline"
              onsubmit="return confirm('Delete this record?');">
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
