@extends('frontend.layouts.app')

@section('title','Permissions')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Header -->
  <div class="page-header d-flex align-items-center mb-4" style="background-color: #041930; border-radius: 0.75rem; padding: 1rem 2rem;">
    <i class="bi bi-shield-lock-fill me-2 fs-3" style="color: #e2ae76;"></i>
    <h2 class="mb-0 fw-bold" style="color: #e2ae76;">Permissions</h2>
  </div>

  <!-- Action Button -->
  <div class="text-end mb-4">
    <a href="{{ route('permissions.create') }}" class="btn btn-gold-blue">
      <i class="bi bi-plus-lg me-2"></i> Add Permission
    </a>
  </div>

  <!-- Permissions Table -->
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-0">
      <table class="table table-hover table-bordered table-striped mb-0">
        <thead style="background-color: #e2ae76; color: #041930;" class="text-center">
          <tr>
            <th style="font-size: 16px; font-weight: 600;">Permission Name</th>
            <th style="font-size: 16px; font-weight: 600;" class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($permissions as $perm)
          <tr>
            <td class="align-middle">{{ $perm->name }}</td>
            <td class="text-end align-middle">
              <a href="{{ route('permissions.edit', $perm) }}" class="btn btn-sm btn-gold me-2">
                <i class="bi bi-pencil me-1"></i> Edit
              </a>
              <form action="{{ route('permissions.destroy', $perm) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this permission?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-red">
                  <i class="bi bi-trash me-1"></i> Delete
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

