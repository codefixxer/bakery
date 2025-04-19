@extends('frontend.layouts.app')
@section('title','Monthly Costs & Income Dashboard')

@section('content')
<div class="container py-5">

  <h3>{{ \Carbon\Carbon::create($year,$month,1)->format('F Y') }}</h3>

  {{-- Month tabs --}}
  <ul class="nav nav-tabs mb-4">
    @for($m=1; $m<=12; $m++)
      <li class="nav-item">
        <a class="nav-link {{ $m==$month?'active':'' }}"
           href="{{ route('costs.dashboard',['m'=>$m,'y'=>$year]) }}">
          {{ \Carbon\Carbon::create($year,$m,1)->format('M') }}
        </a>
      </li>
    @endfor
  </ul>

  {{-- 1) Per‐category costs for this month --}}
  <div class="row g-3 mb-5">
    @foreach($categories as $cat)
      <div class="col-md-4">
        <label class="form-label">{{ $cat->name }}</label>
        <input class="form-control" readonly
               value="${{ number_format($raw->get($cat->id,0),2) }}">
      </div>
    @endforeach
  </div>

  <hr class="my-5">

  {{-- 2) Yearly cost comparison --}}
  <h4>Monthly Cost Comparison</h4>
  <table class="table table-bordered mb-5">
    <thead class="table-light">
      <tr>
        <th>Month</th>
        <th>Cost ({{ $year }})</th>
        <th>Cost ({{ $lastYear }})</th>
      </tr>
    </thead>
    <tbody>
      @for($m=1; $m<=12; $m++)
        <tr>
          <td>{{ \Carbon\Carbon::create($year,$m,1)->format('F') }}</td>
          <td>${{ number_format($costsThisYear->get($m,0),2) }}</td>
          <td>${{ number_format($costsLastYear->get($m,0),2) }}</td>
        </tr>
      @endfor
    </tbody>
  </table>

  <hr class="my-5">

  {{-- 3) Income for this month vs last year --}}
  <h4>This Month’s Income</h4>
  <div class="row g-3 mb-5">
    <div class="col-md-4">
      <label class="form-label">
        Income ({{ $year }}, {{ \Carbon\Carbon::create($year,$month,1)->format('F') }})
      </label>
      <input class="form-control" readonly
             value="${{ number_format($incomeThisMonth,2) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">
        Income ({{ $lastYear }}, same month)
      </label>
      <input class="form-control" readonly
             value="${{ number_format($incomeLastYearSame,2) }}">
    </div>
  </div>

  <hr class="my-5">

  {{-- 4) NEW: Year‐to‐date totals --}}
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Total Costs ({{ $year }})</label>
      <input class="form-control fw-bold" readonly
             value="${{ number_format($totalCostYear,2) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">Total Income ({{ $year }})</label>
      <input class="form-control fw-bold" readonly
             value="${{ number_format($totalIncomeYear,2) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">Net ({{ $year }})</label>
      @php
        $net = $totalIncomeYear - $totalCostYear;
      @endphp
      <input class="form-control {{ $net>=0?'text-success':'text-danger' }}" readonly
             value="${{ number_format($net,2) }}">
    </div>
  </div>

</div>
@endsection
