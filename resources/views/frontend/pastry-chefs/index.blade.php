{{-- resources/views/pastry-chefs/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title','Pastry-chefs Showcase')

@section('content')
<div class="container py-5">
  <div class="card basic-data-table mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">Pastry-chefs Showcase</h5>
      <a href="{{ route('pastry-chefs.create') }}" class="btn btn-light btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Add New
      </a>
    </div>
    <div class="card-body">
      <table
        id="pastryChefsTable"
        class="table bordered-table mb-0"
        data-page-length="10"
      >
        <thead>
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Phone</th>
            <th scope="col">Last Updated</th>
            <th scope="col" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($pastryChefs as $chef)
          <tr>
            <td>{{ $chef->id }}</td>
            <td>{{ $chef->name }}</td>
            <td>{{ $chef->email ?? '-' }}</td>
            <td>{{ $chef->phone ?? '-' }}</td>
            <td>{{ $chef->updated_at->format('Y-m-d H:i') }}</td>
            <td class="text-center">
              <a
                href="{{ route('pastry-chefs.edit', $chef) }}"
                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                title="Edit"
              >
                <iconify-icon icon="lucide:edit"></iconify-icon>
              </a>
              <form
                action="{{ route('pastry-chefs.destroy', $chef) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Delete this chef?');"
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
          @endforeach
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
    $('#pastryChefsTable').DataTable({
      pageLength: $('#pastryChefsTable').data('page-length'),
      responsive: true,
      scrollX: true,
   autoWidth: false,
      columnDefs: [
        { orderable: false, targets: 5 }
      ]
    });
  }
});
</script>
@endsection
