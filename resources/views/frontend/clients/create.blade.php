{{-- resources/views/frontend/clients/form.blade.php --}}
@extends('frontend.layouts.app')

@section('title', isset($client) ? 'Edit Client' : 'Add Client')

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-person-lines-fill fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($client) ? 'Edit' : 'Add' }} Client</h5>
    </div>
    <div class="card-body">
      <form action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}" method="POST">
        @csrf
        @if(isset($client))
          @method('PUT')
        @endif

        <!-- Client Name -->
        <div class="mb-4">
          <label for="name" class="form-label fw-semibold">Client Name</label>
          <input type="text" name="name" id="name" class="form-control"
                 value="{{ old('name', $client->name ?? '') }}" required>
        </div>

        <!-- Location -->
        <div class="mb-4">
          <label for="location" class="form-label fw-semibold">Location</label>
          <input type="text" name="location" id="location" class="form-control"
                 value="{{ old('location', $client->location ?? '') }}">
        </div>

        <!-- Phone -->
        <div class="mb-4">
          <label for="phone" class="form-label fw-semibold">Phone</label>
          <input type="text" name="phone" id="phone" class="form-control"
                 value="{{ old('phone', $client->phone ?? '') }}">
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="form-label fw-semibold">Email</label>
          <input type="email" name="email" id="email" class="form-control"
                 value="{{ old('email', $client->email ?? '') }}">
        </div>

        <!-- Notes -->
        <div class="mb-4">
          <label for="notes" class="form-label fw-semibold">Notes</label>
          <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $client->notes ?? '') }}</textarea>
        </div>

        <!-- Action Buttons -->
        <div class="text-end">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-save2 me-1"></i> {{ isset($client) ? 'Update Client' : 'Save Client' }}
          </button>
          <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-lg ms-2">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
