{{-- resources/views/clients/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title', $client->name)

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header with icon and title -->
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <iconify-icon icon="bi:person-lines-fill" class="fs-2 me-3"></iconify-icon>
      <h4 class="mb-0">{{ $client->name }}</h4>
    </div>

    <div class="card-body">
      <!-- Details in a responsive 2-column grid -->
      <div class="row row-cols-1 row-cols-md-2 g-4 mb-4">
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Location</h6>
          <p class="fs-5 mb-0">{{ $client->location ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Phone</h6>
          <p class="fs-5 mb-0">{{ $client->phone ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Email</h6>
          <p class="fs-5 mb-0">{{ $client->email ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Notes</h6>
          <p class="fs-5 mb-0">{{ $client->notes ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Created At</h6>
          <p class="fs-5 mb-0">{{ optional($client->created_at)->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
        <div class="col">
          <h6 class="text-uppercase text-muted small mb-1">Last Updated</h6>
          <p class="fs-5 mb-0">{{ optional($client->updated_at)->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
      </div>

      <hr class="border-secondary">

      <!-- Action Buttons -->
      <div class="d-flex justify-content-end gap-2">
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-primary btn-lg">
          <iconify-icon icon="bi:-pencil" class="me-1"></iconify-icon>
          Edit
        </a>

        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-lg">
          <iconify-icon icon="bi:arrow-left" class="me-1"></iconify-icon>
          Back to List
        </a>

        <form action="{{ route('clients.destroy', $client) }}"
              method="POST"
              onsubmit="return confirm('Delete this client?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-lg">
            <iconify-icon icon="mingcute:delete-2-line" class="me-1"></iconify-icon>
            Delete
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
