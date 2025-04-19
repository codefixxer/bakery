{{-- resources/views/newss/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'All News')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">News Management</h4>
    <a href="{{ route('newss.create') }}" class="btn btn-success">
      <i class="bi bi-plus-circle me-1"></i> Add News
    </a>
  </div>

  <div class="card basic-data-table mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">News Management</h5>
    </div>
    <div class="card-body">
      <table
        id="newsTable"
        class="table bordered-table mb-0"
        data-page-length="10"
      >
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Content</th>
            <th scope="col">Created</th>
            <th scope="col" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($newsList as $news)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $news->title }}</td>
            <td>{{ \Illuminate\Support\Str::limit(strip_tags($news->content), 100) }}</td>
            <td>{{ $news->created_at->format('d M Y') }}</td>
            <td class="text-center">
              <a
                href="{{ route('newss.edit', $news->id) }}"
                class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center me-1"
                title="Edit"
              >
                <iconify-icon icon="lucide:edit"></iconify-icon>
              </a>
              <form
                action="{{ route('newss.destroy', $news->id) }}"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('Delete this news?');"
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
            <td colspan="5" class="text-center text-muted">No news available.</td>
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
    $('#newsTable').DataTable({
      pageLength: $('#newsTable').data('page-length'),
      responsive: true,
      scrollX: true,
   autoWidth: false,
      columnDefs: [
        { orderable: false, targets: 4 }
      ]
    });
  }
});
</script>
@endsection
