@extends('frontend.layouts.app')

@section('title', $isEdit ? 'Edit User' : 'Add User')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">{{ $isEdit ? 'Edit User' : 'Add User' }}</h2>
  <form action="{{ $isEdit ? route('users.update',$user) : route('users.store') }}"
        method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- Name --}}
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text"
             name="name"
             class="form-control"
             value="{{ old('name', $user->name) }}"
             required>
    </div>

    {{-- Email --}}
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email"
             name="email"
             class="form-control"
             value="{{ old('email', $user->email) }}"
             required>
    </div>

    {{-- Password only on create --}}
    @unless($isEdit)
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input type="password"
             name="password"
             class="form-control"
             required>
    </div>
    @endunless

    {{-- Single‑select Role --}}
    <div class="mb-3">
      <label for="role" class="form-label">Role</label>
      <select id="role"
              name="role"
              class="form-select"
              required>
        <option value="">— Select a role —</option>
        @foreach($roles as $role)
          <option value="{{ $role->id }}"
            {{ (string) old('role', optional($user->roles->first())->id) === (string)$role->id
                ? 'selected' : '' }}>
            {{ ucfirst($role->name) }}
          </option>
        @endforeach
      </select>
    </div>

    <button class="btn btn-success">
      {{ $isEdit ? 'Update' : 'Create' }}
    </button>
  </form>
</div>
@endsection
