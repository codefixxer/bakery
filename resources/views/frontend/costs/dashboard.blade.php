{{-- resources/views/frontend/costs/dashboard.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Monthly Costs & Income Dashboard')

@section('content')
@php use \Carbon\Carbon; @endphp

<style>
  .card-custom-header {
    background-color: #041930;
    color: #e2ae76;
    padding: 0.5rem 1rem;
    font-weight: bold;
    font-size: 1rem;
    border-radius: 0.5rem 0.5rem 0 0;
  }
  .mini-card {
    padding: 0.75rem;
    font-size: 0.8rem;
    border-radius: 0.5rem;
    box-shadow: 0 0 6px rgba(0,0,0,0.1);
    background-color: #fff;
    transition: all 0.3s ease-in-out;
    border: 1px solid #dee2e6;
  }
  .mini-card h3 { font-size: 1.2rem; margin: 0.2rem 0; }
  .mini-card i  { font-size: 1.4rem; margin-bottom: 0.2rem; }
  .mini-card:hover {
    transform: scale(1.05);
    box-shadow: 0 0 10px rgba(0,0,0,0.15);
  }
  .month-tabs .nav-link { font-size: 0.75rem; padding: 0.25rem 0.75rem; }
  .table thead th {
    background-color: #e2ae76;
    color: #041930;
    text-align: center;
    vertical-align: middle;
  }
  .table td, .table th {
    text-align: center;
    vertical-align: middle;
  }
  .row .col-md-3, .row .col-md-4 { padding: 0.25rem; }
</style>

<div class="container py-4">

  <!-- Header + Year Selector -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 text-primary">
      <i class="bi bi-speedometer2 me-2"></i>
      {{ Carbon::create($year, $month, 1)->format('F Y') }}
    </h4>
    <select id="yearSelector" class="form-select form-select-sm w-auto">
      @foreach($availableYears as $availableYear)
        <option value="{{ $availableYear }}" {{ $availableYear == $year ? 'selected' : '' }}>
          {{ $availableYear }}
        </option>
      @endforeach
    </select>
  </div>

  <!-- Month Tabs -->
  <ul class="nav nav-pills mb-4 month-tabs">
    @for($m = 1; $m <= 12; $m++)
      <li class="nav-item">
        <a class="nav-link {{ $m == $month ? 'active' : '' }}"
           href="{{ route('costs.dashboard', ['y' => $year, 'm' => $m]) }}">
          {{ Carbon::create($year, $m, 1)->format('M') }}
        </a>
      </li>
    @endfor
  </ul>

  <!-- Category Summary -->
  <div class="row mb-4 g-3">
    @foreach($categories as $cat)
      <div class="col-6 col-md-3 col-lg-2">
        <div class="mini-card text-center border border-warning">
          <i class="bi bi-tag text-warning"></i>
          <div class="text-muted small">{{ $cat->name }}</div>
          <h3>€{{ number_format($raw[$cat->id] ?? 0, 2) }}</h3>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Monthly Comparison -->
  <div class="card shadow-sm mb-4">
    <div class="card-custom-header">
      <i class="bi bi-bar-chart-line me-2"></i>Monthly Comparison ({{ $year }})
    </div>
    <div class="card-body p-3">
      {{-- Best / Worst alert --}}
      <div class="alert alert-info mb-3 small">
        <strong>Best month:</strong>
          {{ Carbon::create($year, $bestMonth, 1)->format('F') }}
          (€{{ number_format($bestNet, 2) }})
        &nbsp;&nbsp;
        <strong>Worst month:</strong>
          {{ $worstMonth
             ? Carbon::create($year, $worstMonth, 1)->format('F')
             : '—' }}
          (€{{ number_format($worstNet, 2) }})
      </div>

      {{-- Wrap table in a div for AJAX refreshing --}}
      <div id="comparisonTable" class="table-responsive small">
        <table class="table table-bordered align-middle">
          <thead>
            <tr>
              <th>Month</th>
              <th colspan="3">This Year ({{ $year }})</th>
              <th colspan="3">Last Year ({{ $lastYear }})</th>
            </tr>
            <tr>
              <th></th>
              <th>Cost (€)</th><th>Income (€)</th><th>Net (€)</th>
              <th>Cost (€)</th><th>Income (€)</th><th>Net (€)</th>
            </tr>
          </thead>
          <tbody>
            @for($m = 1; $m <= 12; $m++)
              @php
                $c1 = $costsThisYear[$m] ?? 0;
                $i1 = $incomeThisYearMonthly[$m] ?? 0;
                $n1 = $i1 - $c1;
                $c2 = $costsLastYear[$m] ?? 0;
                $i2 = $incomeLastYearMonthly[$m] ?? 0;
                $n2 = $i2 - $c2;
              @endphp
              <tr>
                <td class="text-start">{{ Carbon::create($year, $m, 1)->format('F') }}</td>
                <td>€{{ number_format($c1, 2) }}</td>
                <td>€{{ number_format($i1, 2) }}</td>
                <td class="{{ $n1 >= 0 ? 'text-success' : 'text-danger' }}">
                  €{{ number_format($n1, 2) }}
                </td>
                <td>€{{ number_format($c2, 2) }}</td>
                <td>€{{ number_format($i2, 2) }}</td>
                <td class="{{ $n2 >= 0 ? 'text-success' : 'text-danger' }}">
                  €{{ number_format($n2, 2) }}
                </td>
              </tr>
            @endfor
            <tr class="fw-bold bg-light">
              <td>Total</td>
              <td>€{{ number_format($totalCostYear, 2) }}</td>
              <td>€{{ number_format($totalIncomeYear, 2) }}</td>
              <td class="{{ $netYear >= 0 ? 'text-success' : 'text-danger' }}">
                €{{ number_format($netYear, 2) }}
              </td>
              <td>€{{ number_format($totalCostLastYear, 2) }}</td>
              <td>€{{ number_format($totalIncomeLastYear, 2) }}</td>
              <td class="{{ $netLastYear >= 0 ? 'text-success' : 'text-danger' }}">
                €{{ number_format($netLastYear, 2) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Summary Mini-Cards -->
      <div class="row g-3 text-center mb-4">
        <div class="col-6 col-md-3">
          <div class="mini-card border border-success">
            <i class="bi bi-wallet2 text-success"></i>
            <div>Income ({{ Carbon::create($year, $month, 1)->format('F Y') }})</div>
            <h3>€{{ number_format($incomeThisMonth, 2) }}</h3>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="mini-card border border-secondary">
            <i class="bi bi-wallet text-secondary"></i>
            <div>Income ({{ Carbon::create($lastYear, $month, 1)->format('F Y') }})</div>
            <h3>€{{ number_format($incomeLastYearSame, 2) }}</h3>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="mini-card border border-primary">
            <i class="bi bi-receipt text-primary"></i>
            <div>Total Costs ({{ $year }})</div>
            <h3>€{{ number_format($totalCostYear, 2) }}</h3>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="mini-card border border-success">
            <i class="bi bi-cash-stack text-success"></i>
            <div>Total Income ({{ $year }})</div>
            <h3>€{{ number_format($totalIncomeYear, 2) }}</h3>
          </div>
        </div>
        <div class="col-12 col-md-4 offset-md-4">
          <div class="mini-card border border-danger">
            <i class="bi bi-percent text-danger"></i>
            <div>Net ({{ $year }})</div>
            @php $net = $totalIncomeYear - $totalCostYear; @endphp
            <h3 class="{{ $net >= 0 ? 'text-success' : 'text-danger' }}">
              €{{ number_format($net, 2) }}
            </h3>
          </div>
        </div>
      </div>

    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
  // When you pick a new year, reload the page via named route
  document.getElementById('yearSelector').addEventListener('change', function () {
    const y = this.value, m = {{ $month }};
    window.location.href = `{{ route('costs.dashboard') }}?y=${y}&m=${m}`;
  });

  // (Optional) AJAXify month‐tab clicks so sidebar never re-renders
  $(function(){
    $('.month-tabs a').on('click', function(e){
      e.preventDefault();
      const url = $(this).attr('href');
      $('#comparisonTable').load(url + ' #comparisonTable > *', function(){
        $('.month-tabs .active').removeClass('active');
        $(`.month-tabs a[href="${url}"]`).addClass('active');
        history.pushState(null, '', url);
      });
    });
  });
</script>
@endsection
