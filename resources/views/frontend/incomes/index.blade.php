@extends('frontend.layouts.app')
@section('title','All Incomes')

@section('content')
<div class="container py-5">
  <div class="d-flex justify-content-between mb-3">
    <h3>Recorded Incomes</h3>
    <a href="{{ route('incomes.create') }}" class="btn btn-success">
      <i class="bi bi-plus-lg me-1"></i> Add Income
    </a>
  </div>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>Date</th>
        <th class="text-end">Amount ($)</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($incomes as $inc)
        <tr>
          <td>{{ $inc->date->format('Y-m-d') }}</td>
          <td class="text-end">{{ number_format($inc->amount,2) }}</td>
          <td class="text-center">
            <a href="{{ route('incomes.edit',$inc) }}" class="btn btn-sm btn-outline-primary">
              <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('incomes.destroy',$inc) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Delete this income?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="3" class="text-center">No incomes recorded.</td></tr>
      @endforelse
    </tbody>
  </table>

  {{ $incomes->links() }}
</div>
@endsection
