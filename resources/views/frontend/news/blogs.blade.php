@extends('frontend.layouts.app')

@section('title', 'News & Blog')

@section('content')
<div class="container py-5">

    <!-- Top-Rated & Featured Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="text-center text-dark">Top Rated Blogs</h2>
            <p class="text-center text-muted">Discover our most popular and top-rated blogs for insights and expert advice.</p>
        </div>
    </div>

    <div class="row gy-4">
        @foreach($news as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-lg rounded-3 overflow-hidden">
                    <div class="card-header" style="background-color: #041930; color: #e2ae76;">
                        <h5 class="mb-0" style="background-color: #041930; color: #e2ae76;">{{ $item->title }}</h5>
                        <span class="badge bg-warning text-dark">{{ $item->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ Str::limit($item->content, 150) }}</p>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('news.show', $item->id) }}" class="btn btn-primary btn-sm">Read More</a>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Empty state if no news -->
        @if($news->isEmpty())
            <div class="col-12 text-center text-muted">
                <p>No news posts found.</p>
            </div>
        @endif
    </div>

</div>
@endsection
