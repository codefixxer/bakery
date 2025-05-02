{{-- resources/views/frontend/roles/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Roles & Permissions')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between mb-3">
    <h2>Roles</h2>
    <a href="{{ route('roles.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg"></i> Add Role
    </a>
  </div>
  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead class="table-light">
          <tr><th>Name</th><th>Permissions</th><th class="text-end">Actions</th></tr>
        </thead>
        <tbody>
          @foreach($roles as $role)
          <tr>
            <td>{{ $role->name }}</td>
            <td>
              @foreach($role->permissions as $p)
                <span class="badge bg-info">{{ $p->name }}</span>
              @endforeach
            </td>
            <td class="text-end">
              <a href="{{ route('roles.edit',$role) }}" class="btn btn-sm btn-outline-primary">Edit</a>
              <form action="{{ route('roles.destroy',$role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete role?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
