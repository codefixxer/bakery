@extends('frontend.layouts.app')

@section('title', 'All News')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">All News</h4>
        <a href="{{ route('news.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Add New News
        </a>
    </div>

    <div class="card basic-data-table mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">All News</h5>
        </div>
        <div class="card-body">
            <table class="table bordered-table mb-0">
                <thead>
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
                        <td>{{ $item->event_date }}</td>
                        <td>{{ $item->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <a href="{{ route('news.edit', $item) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
