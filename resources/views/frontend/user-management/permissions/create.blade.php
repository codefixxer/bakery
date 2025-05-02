@extends('frontend.layouts.app')

@section('title', $isEdit ? 'Edit Permission' : 'Add Permission')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">{{ $isEdit ? 'Edit Permission' : 'Add Permission' }}</h2>

  <form action="{{ $isEdit ? route('permissions.update',$permission) : route('permissions.store') }}"
        method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="mb-3">
      <label class="form-label">Permission Name</label>
      <input type="text" name="name"
             class="form-control"
             value="{{ old('name',$permission->name ?? '') }}"
             required>
    </div>

    <button class="btn btn-success">
      {{ $isEdit ? 'Update' : 'Create' }}
    </button>
  </form>
</div>
@endsection
