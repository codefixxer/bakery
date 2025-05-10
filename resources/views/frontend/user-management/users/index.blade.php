{{-- resources/views/frontend/user-management/users/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Users')

@section('content')
<div class="container py-5 px-md-4">

  {{-- Logged-in User Profile Card --}}
  <div class="row mb-5 justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-lg rounded-3 border-0 overflow-hidden">

        <div class="card-body text-center pt-5">
          <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
          <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
          <div class="mb-3">
            @forelse(auth()->user()->roles as $role)
              <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
            @empty
              <span class="text-secondary">No roles assigned</span>
            @endforelse
          </div>
          <a href="{{ route('users.show', auth()->user()) }}"
             class="btn btn-outline-secondary btn-sm me-2">
            <i class="bi bi-eye me-1"></i>View Profile
          </a>
          <a href="{{ route('users.edit', auth()->user()) }}"
             class="btn btn-outline-primary btn-sm me-2">
            <i class="bi bi-pencil me-1"></i>Edit Profile
          </a>
          <a href="{{ route('logout') }}" 
             onclick="event.preventDefault();document.getElementById('logout-form').submit();"
             class="btn btn-outline-danger btn-sm">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Page Header & Add User Button --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-header d-flex align-items-center mb-0">
      <i class="bi bi-people-fill me-2 fs-3" style="color: #e2ae76;"></i>
      <h2 class="mb-0 fw-bold" style="color: #041930;">Users</h2>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-gold-blue btn-lg">
      <i class="bi bi-plus-lg me-1"></i> Add User
    </a>
  </div>

  {{-- Success Message --}}
  @if(session('success'))
    <div class="alert alert-success mb-4 p-3 rounded-3 shadow-sm">
      <i class="bi bi-check-circle-fill me-2"></i>
      <strong>Success!</strong> {{ session('success') }}
    </div>
  @endif

  {{-- Users Table --}}
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
                <span class="badge bg-secondary">{{ ucfirst($r->name) }}</span>
              @empty
                <em class="text-muted">—</em>
              @endforelse
            </td>
            <td class="text-center">
              <span class="badge {{ $u->status ? 'bg-success' : 'bg-danger' }}">
                {{ $u->status ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td class="text-end">
              <a href="{{ route('users.show', $u) }}" class="btn btn-sm btn-deepblue me-1">
                <i class="bi bi-eye"></i> View
              </a>
              <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-gold me-1">
                <i class="bi bi-pencil"></i> Edit
              </a>
              @if(auth()->user()->hasRole('super') && auth()->id() !== $u->id)
                <form action="{{ route('users.toggleStatus', $u->id) }}" method="POST" class="d-inline">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-sm {{ $u->status ? 'btn-red' : 'btn-deepblue' }} me-1">
                    {{ $u->status ? 'Deactivate' : 'Activate' }}
                  </button>
                </form>
              <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-red"><i class="bi bi-trash"></i> Delete</button>
              </form>
                            @endif

            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="mt-4">
    {{ $users->links() }}
  </div>
</div>

{{-- Styles --}}
<style>
  .bg-gradient {
    background: linear-gradient(135deg, #041930 0%, #e2ae76 100%);
  }
  .avatar-wrapper {
    width: 120px; height: 120px;
    overflow: hidden; border-radius: 50%;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
  }
  .btn-gold-blue {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    border: 1px solid #e2ae76;
  }
  .btn-gold-blue:hover { background-color: #d89d5c !important; color: #fff !important; }
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #e2ae76 !important;
    background-color: transparent !important;
  }
  .btn-gold:hover { background-color: #e2ae76 !important; color: #fff !important; }
  .btn-deepblue {
    border: 1px solid #041930 !important;
    color: #041930 !important;
    background-color: transparent !important;
  }
  .btn-deepblue:hover { background-color: #041930 !important; color: #fff !important; }
  .btn-red {
    border: 1px solid red !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover { background-color: red !important; color: #fff !important; }
</style>
@endsection
