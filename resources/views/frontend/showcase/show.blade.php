@extends('frontend.layouts.app')
@section('title', 'Showcase on '.$showcase->showcase_date)
@section('content')
<div class="container py-5">
  <a href="{{ route('showcase.index') }}" class="btn btn-outline-secondary mb-3">
    ← Back to all showcases
  </a>

  <h3>Showcase for {{ $showcase->showcase_date }}</h3>
  <div class="row mb-4 gx-3">
    <div class="col-md-3"><strong>Break-even:</strong> €{{ number_format($showcase->break_even,2) }}</div>
    <div class="col-md-3"><strong>Total Revenue:</strong> €{{ number_format($showcase->total_revenue,2) }}</div>
    <div class="col-md-3"><strong>Potential Avg:</strong> €{{ number_format($showcase->potential_income_average,2) }}</div>
    <div class="col-md-3"><strong>Real Margin:</strong> {{ $showcase->real_margin }}%</div>
  </div>

  <div class="table-responsive">
    <table id="detailTable" class="table table-bordered table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>Recipe</th>
          <th>Department</th>
          <th class="text-center">Qty</th>
          <th class="text-center">Sold</th>
          <th class="text-center">Reuse</th>
          <th class="text-center">Waste</th>
          <th class="text-end">Potential (€)</th>
          <th class="text-end">Actual (€)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($showcase->recipes as $sr)
          <tr>
            <td>{{ $sr->recipe->recipe_name }}</td>
            <td>{{ $sr->recipe->department->name }}</td>
            <td class="text-center">{{ $sr->quantity }}</td>
            <td class="text-center">{{ $sr->sold }}</td>
            <td class="text-center">{{ $sr->reuse }}</td>
            <td class="text-center">{{ $sr->waste }}</td>
            <td class="text-end">{{ number_format($sr->potential_income,2) }}</td>
            <td class="text-end">{{ number_format($sr->actual_revenue,2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(function(){
    $('#detailTable').DataTable({
      pageLength: 25,
      responsive: true,
      ordering: true
    });
  });
</script>
@endsection
