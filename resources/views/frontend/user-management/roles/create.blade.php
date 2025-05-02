@extends('frontend.layouts.app')

@section('title', $isEdit ? 'Edit Role' : 'Add Role')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">{{ $isEdit ? 'Edit Role' : 'Add Role' }}</h2>

  <form action="{{ $isEdit ? route('roles.update',$role) : route('roles.store') }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="mb-3">
      <label class="form-label">Role Name</label>
      <input type="text" name="name"
             class="form-control"
             value="{{ old('name',$role->name ?? '') }}"
             required>
    </div>

    <div class="mb-3">
      <label class="form-label">Permissions</label>
      <div class="row">
        @foreach($permissions as $perm)
          <div class="col-md-4">
            <div class="form-check">
              <input class="form-check-input"
                     type="checkbox"
                     name="permissions[]"
                     value="{{ $perm->id }}"
                     id="perm_{{ $perm->id }}"
                     {{ in_array(
                      $perm->id,
                      old('permissions',
                          $isEdit
                              ? $role->permissions->pluck('id')->toArray()
                              : []
                      )
                  ) ? 'checked' : '' }}
>                  
              <label class="form-check-label"
                     for="perm_{{ $perm->id }}">
                {{ $perm->name }}
              </label>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <button class="btn btn-success">
      {{ $isEdit ? 'Update' : 'Create' }}
    </button>
  </form>
</div>
@endsection
