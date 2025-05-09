@extends('frontend.layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Header -->
  <div class="page-header d-flex align-items-center mb-4" style="background-color: #041930; border-radius: 0.75rem; padding: 1rem 2rem;">
    <i class="bi bi-shield-lock me-2 fs-3" style="color: #e2ae76;"></i>
    <h2 class="mb-0 fw-bold" style="color: #e2ae76;">Roles & Permissions</h2>
  </div>

  <!-- Add Button -->
  <div class="d-flex justify-content-end mb-3">
    <a href="{{ route('roles.create') }}" class="btn btn-gold-blue">
      <i class="bi bi-plus-circle me-1"></i> Add Role
    </a>
  </div>

  <!-- Table -->
  <div class="card shadow-lg border-0 rounded-3">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-striped mb-0">
          <thead style="background-color: #e2ae76; color: #041930;">
            <tr class="text-center">
              <th style="font-size: 16px; font-weight: 600;">Role Name</th>
              <th style="font-size: 16px; font-weight: 600;">Permissions</th>
              <th style="font-size: 16px; font-weight: 600;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($roles as $role)
              <tr>
                <td class="align-middle text-center fw-semibold">{{ ucfirst($role->name) }}</td>
                <td class="align-middle text-center">
                  @forelse($role->permissions as $permission)
                    <span class="badge bg-secondary text-light m-1">{{ $permission->name }}</span>
                  @empty
                    <span class="text-muted">—</span>
                  @endforelse
                </td>
                <td class="text-center align-middle">
                  <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-gold me-1">
                    <i class="bi bi-pencil"></i> Edit
                  </a>
                  <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete role?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-red">
                      <i class="bi bi-trash"></i> Delete
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

<style>
  .btn-gold-blue {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    border: 1px solid #e2ae76;
  }
  .btn-gold-blue:hover {
    background-color: #d89d5c !important;
    color: white !important;
  }
  .btn-gold {
    border: 1px solid #e2ae76 !important;
    color: #e2ae76 !important;
    background-color: transparent !important;
  }
  .btn-gold:hover {
    background-color: #e2ae76 !important;
    color: #041930 !important;
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
</style>

