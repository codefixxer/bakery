{{-- resources/views/frontend/users/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Users')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between mb-3">
    <h2>Users</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg"></i> Add User
    </a>
  </div>
  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead class="table-light">
          <tr>
            <th>Name</th><th>Email</th><th>Roles</th><th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              @foreach($user->roles as $r)
                <span class="badge bg-secondary">{{ $r->name }}</span>
              @endforeach
            </td>
            <td class="text-end">
              <a href="{{ route('users.show',$user) }}" class="btn btn-sm btn-outline-info">View</a>
              <a href="{{ route('users.edit',$user) }}" class="btn btn-sm btn-outline-primary">Edit</a>
              <form action="{{ route('users.destroy',$user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
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
