@extends('frontend.layouts.app')

@section('title','Users')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Users</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ Add User</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Roles</th> 
        <th>Status</th>
        <th class="text-end">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $u)
        <tr>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>
            @forelse($u->roles as $r)
              <span class="badge bg-secondary">{{ $r->name }}</span>
            @empty
              <em>—</em>
            @endforelse
          </td>
          <td>
            @if($u->status)
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-danger">Inactive</span>
            @endif
          </td>
          <td class="text-end">
            <a href="{{ route('users.show', $u) }}" class="btn btn-sm btn-outline-primary">View</a>
            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-secondary">Edit</a>

            @if(auth()->user()->hasRole('super') && auth()->id() !== $u->id)
              <form action="{{ route('users.toggleStatus', $u->id) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $u->status ? 'btn-danger' : 'btn-success' }}">
                  {{ $u->status ? 'Deactivate' : 'Activate' }}
                </button>
              </form>
            @endif

            <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $users->links() }}
</div>
@endsection
