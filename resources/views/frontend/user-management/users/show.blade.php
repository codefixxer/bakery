{{-- resources/views/frontend/users/show.blade.php --}}
@extends('frontend.layouts.app')

@section('title','User Details')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">User: {{ $user->name }}</h2>
  <div class="card mb-4">
    <div class="card-body">
      <p><strong>Email:</strong> {{ $user->email }}</p>
      <p><strong>Roles:</strong>
        @foreach($user->roles as $r)
          <span class="badge bg-secondary">{{ $r->name }}</span>
        @endforeach
      </p>
    </div>
  </div>
  <a href="{{ route('users.edit',$user) }}" class="btn btn-primary">Edit</a>
  <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back to list</a>
</div>
@endsection
