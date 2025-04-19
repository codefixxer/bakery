


{{-- resources/views/clients/create.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Add Client')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">Add New Client</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}" method="POST">
  @csrf
  @if(isset($client))
    @method('PUT')
  @endif
    <div class="mb-3">
      <label for="name" class="form-label">Client Name</label>
      <input type="text" name="name" id="name" class="form-control"
       value="{{ old('name', $client->name ?? '') }}" required>

    </div>
    <div class="mb-3">
      <label for="location" class="form-label">Location</label>
      <input type="text" name="location" id="location" class="form-control"
       value="{{ old('location', $client->location ?? '') }}">
    </div>
    <div class="mb-3">
      <label for="phone" class="form-label">Phone</label>
      
<input type="text" name="phone" id="phone" class="form-control"
       value="{{ old('phone', $client->phone ?? '') }}">
    </div>
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" id="email" class="form-control"
       value="{{ old('email', $client->email ?? '') }}">
    </div>
    <div class="mb-3">
      <label for="notes" class="form-label">Notes</label>
      <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $client->notes ?? '') }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">
  {{ isset($client) ? 'Update Client' : 'Save Client' }}
</button>

    <a href="{{ route('clients.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection
