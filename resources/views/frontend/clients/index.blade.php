{{-- resources/views/clients/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Clients')

@section('content')
<div class="container py-5">

  <!-- Add / Edit Client Form -->
  <div class="card mb-5 border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex align-items-center">
      <i class="bi bi-person-lines-fill fs-4 me-2"></i>
      <h5 class="mb-0">{{ isset($client) ? 'Edit Client' : 'Add Client' }}</h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}"
        method="POST"
        class="row g-3 needs-validation"
        novalidate
      >
        @csrf
        @if(isset($client)) @method('PUT') @endif

        <div class="col-md-6">
          <label for="name" class="form-label fw-semibold">Client Name</label>
          <input
            type="text"
            name="name"
            id="name"
            class="form-control form-control-lg"
            value="{{ old('name', $client->name ?? '') }}"
            required
          >
          <div class="invalid-feedback">
            Please provide a client name.
          </div>
        </div>

        <div class="col-md-6">
          <label for="location" class="form-label fw-semibold">Location</label>
          <input
            type="text"
            name="location"
            id="location"
            class="form-control form-control-lg"
            value="{{ old('location', $client->location ?? '') }}"
          >
        </div>

        <div class="col-md-4">
          <label for="phone" class="form-label fw-semibold">Phone</label>
          <input
            type="text"
            name="phone"
            id="phone"
            class="form-control form-control-lg"
            value="{{ old('phone', $client->phone ?? '') }}"
          >
        </div>

        <div class="col-md-4">
          <label for="email" class="form-label fw-semibold">Email</label>
          <input
            type="email"
            name="email"
            id="email"
            class="form-control form-control-lg"
            value="{{ old('email', $client->email ?? '') }}"
          >
        </div>

        <div class="col-md-4">
          <label for="notes" class="form-label fw-semibold">Notes</label>
          <input
            type="text"
            name="notes"
            id="notes"
            class="form-control form-control-lg"
            value="{{ old('notes', $client->notes ?? '') }}"
          >
        </div>

        <div class="col-12 text-end">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="bi bi-save2 me-1"></i>
            {{ isset($client) ? 'Update Client' : 'Save Client' }}
          </button>
          <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-lg ms-2">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Clients Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Clients</h5>
      <a href="{{ route('clients.create') }}" class="btn btn-light">
        <i class="bi bi-plus-circle me-1 text-primary"></i>
        New Client
      </a>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table
          id="clientsTable"
          class="table table-striped table-hover table-bordered align-middle mb-0"
          data-page-length="10"
        >
          <thead class="table-primary">
            <tr>
              <th>Name</th>
              <th>Location</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Notes</th>
              <th class="text-center">Actions</th>
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
                    class="btn btn-sm btn-outline-info me-1"
                    title="View"
                  >
                    <i class="bi bi-eye"></i>
                  </a>
                  <a
                    href="{{ route('clients.edit', $client) }}"
                    class="btn btn-sm btn-outline-primary me-1"
                    title="Edit"
                  >
                    <i class="bi bi-pencil"></i>
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
                      class="btn btn-sm btn-outline-danger"
                      title="Delete"
                    >
                      <i class="bi bi-trash"></i>
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
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // DataTable initialization
    if (window.$ && $.fn.DataTable) {
      $('#clientsTable').DataTable({
        paging: true,
        ordering: true,
        responsive: true,
        pageLength: $('#clientsTable').data('page-length'),
        columnDefs: [{ orderable: false, targets: 5 }]
      });
    }

    // Bootstrap validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', e => {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  });
</script>
@endsection
