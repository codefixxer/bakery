@extends('frontend.layouts.app')

@section('title', 'All News')

@section('content')
<div class="container py-5 px-md-5">
    <div class="d-flex justify-content-between align-items-center page-header mb-4">
        <div class="d-flex align-items-center">
          <i class="bi bi-megaphone fs-3 me-2"></i>
          <h4 class="mb-0 fw-bold">All News</h4>
        </div>
        <a href="{{ route('news.create') }}" class="btn btn-gold-filled btn-lg">
          <i class="bi bi-plus-circle me-1"></i> Add News
        </a>
      </div>
      

    <div class="card border-primary shadow-sm mt-50">
        <div class="card-header d-flex align-items-center" style="background-color: #041930;">
            <i class="bi bi-newspaper fs-4 me-2" style="color: #e2ae76;"></i>
            <h5 class="mb-0 fw-bold" style="color: #e2ae76;">News List</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle text-center mb-0">
                <thead style="background-color: #e2ae76; color: #041930;">
                    <tr>
                        <th>Title</th>
                        <th>Event Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($news as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->event_date)->format('Y-m-d') }}</td>
                        <td>
                            <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('news.edit', $item) }}" class="btn btn-sm btn-gold me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-red" title="Delete">
                                    <i class="bi bi-trash"></i>
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
    .btn-gold-filled {
        background-color: #e2ae76 !important;
        color: #041930 !important;
        border: none !important;
        font-weight: 500;
        padding: 8px 20px;
        border-radius: 10px;
        transition: background-color 0.2s ease;
    }

    .btn-gold-filled:hover {
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
        color: white !important;
    }

    .btn-red {
        border: 1px solid #dc2626 !important;
        color: #dc2626 !important;
        background-color: transparent !important;
    }

    .btn-red:hover {
        background-color: #dc2626 !important;
        color: white !important;
    }

    table th, table td {
        vertical-align: middle !important;
    }
    .page-header {
  background-color: #041930;
  color: #e2ae76;
  padding: 1rem 1.5rem;
  border-radius: 0.75rem;
}
.page-header h4 {
  color: #e2ae76;
}
.page-header i {
  color: #e2ae76;
}

</style>
