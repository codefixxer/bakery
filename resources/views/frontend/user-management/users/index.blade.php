@extends('frontend.layouts.app')

@section('title','Users')

@section('content')
<div class="container py-5 px-md-4">

  <!-- Page Header -->
  <div class="page-header d-flex align-items-center mb-4" style="background-color: #041930; border-radius: 0.75rem; padding: 1rem 2rem;">
    <i class="bi bi-people-fill me-2 fs-3" style="color: #e2ae76;"></i>
    <h2 class="mb-0 fw-bold" style="color: #e2ae76;">Users</h2>
  </div>

  <div class="d-flex justify-content-end mb-4">
    <a href="{{ route('users.create') }}" class="btn btn-gold-blue btn-lg">
      <i class="bi bi-plus-lg me-1"></i> Add User
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success mb-4 p-3 rounded-3 shadow-sm">
      <strong>Success!</strong> {{ session('success') }}
    </div>
  @endif

  <div class="table-responsive">
    <table class="table table-hover table-bordered shadow-sm rounded">
      <thead style="background-color: #e2ae76; color: #041930;">
        <tr class="text-center fw-semibold">
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
            <td class="text-center">
              @if($u->status)
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-danger">Inactive</span>
              @endif
            </td>
            <td class="text-end">
              <a href="{{ route('users.show', $u) }}" class="btn btn-sm btn-deepblue me-1">View</a>
              <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-gold me-1">Edit</a>

              @if(auth()->user()->hasRole('super') && auth()->id() !== $u->id)
                <form action="{{ route('users.toggleStatus', $u->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="btn btn-sm {{ $u->status ? 'btn-red' : 'btn-deepblue' }} me-1">
                    {{ $u->status ? 'Deactivate' : 'Activate' }}
                  </button>
                </form>
              @endif

              <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-red">Delete</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $users->links() }}
  </div>
</div>

<style>
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #e2ae76 !important;
    background-color: transparent !important;
  }
  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: white !important;
  }

  .btn-deepblue {
    border: 1px solid #041930 !important;
    color: #041930 !important;
    background-color: transparent !important;
  }
  .btn-deepblue:hover {
    background-color: #041930 !important;
    color: white !important;
  }

  .btn-red {
    border: 1px solid red !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: red !important;
    color: white !important;
  }

  .btn-gold-blue {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    border: 1px solid #e2ae76;
  }
  .btn-gold-blue:hover {
    background-color: #d89d5c !important;
    color: white !important;
  }
</style>
@endsection
