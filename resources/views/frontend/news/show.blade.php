{{-- resources/views/frontend/news/show.blade.php --}}

@extends('frontend.layouts.app')

@section('title', $news->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg rounded-3">
                <div class="card-header" style="background-color: #041930; color: #e2ae76;">
                    <h2 class="mb-0" style="background-color: #041930; color: #e2ae76;" >{{ $news->title }}</h2>
                    <span class="badge bg-warning text-dark">{{ $news->created_at->diffForHumans() }}</span>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ $news->content }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
