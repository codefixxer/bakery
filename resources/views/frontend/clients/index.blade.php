{{-- resources/views/clients/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', isset($client) ? 'Edit Client' : 'Add Client')


 



 


@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-person-lines-fill fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($client) ? 'Edit' : 'Add' }} Client</h5>
    </div>
    <div class="card-body">
      <form action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}" method="POST">
        @csrf
        @if(isset($client))
          @method('PUT')
        @endif

        <!-- Client Name -->
        <div class="mb-4">
          <label for="name" class="form-label fw-semibold">Client Name</label>
          <input type="text" name="name" id="name" class="form-control"
                 value="{{ old('name', $client->name ?? '') }}" required>
        </div>

        <!-- Location -->
        <div class="mb-4">
          <label for="location" class="form-label fw-semibold">Location</label>
          <input type="text" name="location" id="location" class="form-control"
                 value="{{ old('location', $client->location ?? '') }}">
        </div>

        <!-- Phone -->
        <div class="mb-4">
          <label for="phone" class="form-label fw-semibold">Phone</label>
          <input type="text" name="phone" id="phone" class="form-control"
                 value="{{ old('phone', $client->phone ?? '') }}">
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="form-label fw-semibold">Email</label>
          <input type="email" name="email" id="email" class="form-control"
                 value="{{ old('email', $client->email ?? '') }}">
        </div>

        <!-- Notes -->
        <div class="mb-4">
          <label for="notes" class="form-label fw-semibold">Notes</label>
          <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $client->notes ?? '') }}</textarea>
        </div>

        <!-- Action Buttons -->
        <div class="text-end">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-save2 me-1"></i> {{ isset($client) ? 'Update Client' : 'Save Client' }}
          </button>
          <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-lg ms-2">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
 








<div class="container py-5">
   

 

  <div class="card basic-data-table mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">Clients</h5>
    </div>
    <div class="card-body">
      <table
        class="table bordered-table mb-0"
        id="clientsTable"
        data-page-length="10"
      >
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Location</th>
            <th scope="col">Phone</th>
            <th scope="col">Email</th>
            <th scope="col">Notes</th>
            <th scope="col" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($clients as $client)
          <tr>
            <td>{{ $client->name }}</td>
            <td>{{ $client->location }}</td>
            <td>{{ $client->phone }}</td>
            <td>{{ $client->email }}</td>
            <td>{{ \Illuminate\Support\Str::limit($client->notes, 50) }}</td>
            <td class="text-center">
              <a
                href="{{ route('clients.show', $client) }}"
                class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                title="View"
              >
                <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
              </a>
              <a
                href="{{ route('clients.edit', $client) }}"
                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                title="Edit"
              >
                <iconify-icon icon="lucide:edit"></iconify-icon>
              </a>
              <form
                action="{{ route('clients.destroy', $client) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Delete this client?');"
              >
                @csrf
                @method('DELETE')
                <button
                  type="submit"
                  class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                  title="Delete"
                >
                  <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center">No clients found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  if (window.$ && $.fn.DataTable) {
    $('#clientsTable').DataTable({
      pageLength: $('#clientsTable').data('page-length'),
      responsive: true,
      scrollX: true,
   autoWidth: false,
      columnDefs: [
        // disable ordering on the Actions column (zero-based index 5)
        { orderable: false, targets: 5 }
      ]
    });
  }
});
</script>
@endsection
