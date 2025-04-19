{{-- resources/views/clients/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Clients')

@section('content')
<div class="container py-5">
  <h2 class="mb-4">Clients</h2>

  <div class="mb-3">
    <a href="{{ route('clients.create') }}" class="btn btn-primary">Add New Client</a>
  </div>

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
