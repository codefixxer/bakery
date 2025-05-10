@extends('frontend.layouts.app')

@section('title', 'Clients')

@section('content')
<div class="container py-5 px-md-5">

  <!-- Add / Edit Client Form -->
  <div class="card border-primary shadow-sm mb-5">
    <div class="card-header d-flex align-items-center" style="background-color: #041930;">
      <i class="bi bi-person-lines-fill fs-4 me-2" style="color: #e2ae76;"></i>
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        {{ isset($client) ? 'Edit Client' : 'Add Client' }}
      </h5>
    </div>
    <div class="card-body">
      <form
        action="{{ isset($client) ? route('clients.update', $client->id) : route('clients.store') }}"
        method="POST"
        class="row g-3 needs-validation"
        novalidate>
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
            required>
          <div class="invalid-feedback">Please provide a client name.</div>
        </div>

        <div class="col-md-6">
          <label for="location" class="form-label fw-semibold">Location</label>
          <input
            type="text"
            name="location"
            id="location"
            class="form-control form-control-lg"
            value="{{ old('location', $client->location ?? '') }}">
        </div>

        <div class="col-md-4">
          <label for="phone" class="form-label fw-semibold">Phone</label>
          <input
            type="text"
            name="phone"
            id="phone"
            class="form-control form-control-lg"
            value="{{ old('phone', $client->phone ?? '') }}">
        </div>

        <div class="col-md-4">
          <label for="email" class="form-label fw-semibold">Email</label>
          <input
            type="email"
            name="email"
            id="email"
            class="form-control form-control-lg"
            value="{{ old('email', $client->email ?? '') }}">
        </div>

        <div class="col-md-4">
          <label for="notes" class="form-label fw-semibold">Notes</label>
          <input
            type="text"
            name="notes"
            id="notes"
            class="form-control form-control-lg"
            value="{{ old('notes', $client->notes ?? '') }}">
        </div>

        <div class="col-12 text-end">
          <button
            type="submit"
            class="btn btn-lg fw-semibold"
            style="background-color: #e2ae76; color: #041930;">
            <i class="bi bi-save2 me-2"></i>
            {{ isset($client) ? 'Update Client' : 'Save Client' }}
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Clients Table -->
  <div class="card border-primary shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #041930;">
      <h5 class="mb-0 fw-bold" style="color: #e2ae76;">
        <i class="bi bi-people me-2"></i> Clients
      </h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table
          id="clientsTable"
          class="table table-bordered table-striped table-hover align-middle mb-0 text-center"
          data-page-length="10">
          <thead style="background-color: #e2ae76;">
            <tr>
              <th>Name</th>
              <th>Location</th>
              <th>Phone</th>
              <th>Email</th>
              <th>Notes</th>
              <th>Actions</th>
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
                <td>
                  <a
                    href="{{ route('clients.show', $client) }}"
                    class="btn btn-sm btn-deepblue me-1"
                    title="View">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a
                    href="{{ route('clients.edit', $client) }}"
                    class="btn btn-sm btn-gold me-1"
                    title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form
                    action="{{ route('clients.destroy', $client) }}"
                    method="POST"
                    class="d-inline"
                    onsubmit="return confirm('Delete this client?');">
                    @csrf
                    @method('DELETE')
                    <button
                      type="submit"
                      class="btn btn-sm btn-red"
                      title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-muted">No clients found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        <div class="mt-3">
          {{ $clients->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (window.$ && $.fn.DataTable) {
      // suppress DataTables alert popups
      $.fn.dataTable.ext.errMode = 'none';

      $('#clientsTable').DataTable({
        paging:      true,
        ordering:    true,
        responsive:  true,
        pageLength:  $('#clientsTable').data('page-length') || 10,
        columnDefs: [
          { orderable: false, targets: -1 }  // disable ordering only on Actions column
        ]
      });
    }

    // Bootstrap client-side validation
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
    border: 1px solid #ff0000 !important;
    color: red !important;
    background-color: transparent !important;
  }
  .btn-red:hover {
    background-color: #ff0000 !important;
    color: white !important;
  }
  table thead th {
    background-color: #e2ae76 !important;
    color: #041930 !important;
    text-align: center !important;
    vertical-align: middle !important;
  }

  /* Also center the table body cells */
  table tbody td {
    text-align: center !important;
    vertical-align: middle !important;
  }

  /* Optional: Make sorting icons visible */
  table thead .sorting:after,
  table thead .sorting_asc:after,
  table thead .sorting_desc:after {
    color: #041930 !important;
  }
</style>

