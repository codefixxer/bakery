{{-- resources/views/external-supplies/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'External Supplies')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">External Supplies</h4>
    <a href="{{ route('external-supplies.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle me-1"></i> Add Supply
    </a>
  </div>

  <div class="row row-cols-1 row-cols-sm-2 g-4">
    @forelse($externalSupplies as $supply)
      <div class="col">
        <div class="card h-100 shadow-sm">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0">{{ $supply->client->name }}</h5>
              <small class="text-light">{{ $supply->supply_date }}</small>
            </div>
            <span class="badge bg-white text-primary">SUPPLY</span>
          </div>
          <div class="card-body d-flex flex-column">
            <ul class="list-unstyled mb-3">
              <li><strong>Total Cost:</strong> ${{ number_format($supply->total_amount, 2) }}</li>
            </ul>

            <h6 class="fw-bold">Supplied Recipes</h6>
            <div class="mt-2 mb-3 flex-grow-1">
              @foreach($supply->recipes as $item)
                <div class="d-flex justify-content-between align-items-center border-bottom py-1">
                  <div>
                    {{ $item->recipe->recipe_name ?? 'N/A' }}
                    <span class="text-muted">({{ $item->qty }} qty)</span>
                  </div>
                  <span class="badge bg-dark">${{ number_format($item->total_amount, 2) }}</span>
                </div>
              @endforeach
            </div>

            <div class="mt-auto">
              <div class="d-flex flex-column flex-sm-row justify-content-between small text-muted">
                <div>
                  <div>Created: {{ $supply->created_at->format('Y-m-d') }}</div>
                  <div>Updated: {{ $supply->updated_at->format('Y-m-d') }}</div>
                </div>
                <div class="mt-2 mt-sm-0">
                  <a href="{{ route('external-supplies.edit', $supply->id) }}"
                     class="btn btn-sm btn-outline-primary me-1"
                     title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('external-supplies.destroy', $supply->id) }}"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Delete this supply?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center text-muted">
        No supplies found.
      </div>
    @endforelse
  </div>
</div>
@endsection
