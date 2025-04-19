{{-- resources/views/costs/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All Costs')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Costs</h4>
    <a href="{{ route('costs.create') }}" class="btn btn-success">
      <i class="bi bi-plus-circle me-1"></i> Add New Cost
    </a>
  </div>

  <div class="card basic-data-table mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">All Costs</h5>
    </div>
    <div class="card-body">
      <table
        id="costTable"
        class="table bordered-table mb-0"
        data-page-length="10"
      >
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Supplier</th>
            <th scope="col">Amount</th>
            <th scope="col">Due Date</th>
            <th scope="col">Category</th>
            <th scope="col" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($costs as $cost)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $cost->supplier }}</td>
            <td>${{ number_format($cost->amount, 2) }}</td>
            <td>{{ $cost->due_date }}</td>
            <td>{{ $cost->category->name ?? 'N/A' }}</td>
            <td class="text-center">
              <a
                href="{{ route('costs.edit', $cost->id) }}"
                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                title="Edit"
              >
                <iconify-icon icon="lucide:edit"></iconify-icon>
              </a>
              <form
                action="{{ route('costs.destroy', $cost->id) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Are you sure you want to delete this cost?');"
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
            <td colspan="6" class="text-center text-muted">No costs found.</td>
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
    $('#costTable').DataTable({
      pageLength: $('#costTable').data('page-length'),
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
