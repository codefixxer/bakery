@extends('frontend.layouts.app')

@section('title', 'Create News')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Create News</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('news.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea id="content" name="content" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="event_date" class="form-label">Event Date</label>
                    <input type="date" id="event_date" name="event_date" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Create News</button>
            </form>
        </div>
    </div>
</div>
@endsection
