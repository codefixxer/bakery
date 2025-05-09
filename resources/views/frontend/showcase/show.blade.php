@extends('frontend.layouts.app')

@section('title', $showcase->showcase_date)

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header with icon and date -->
    <div class="card-header" style="background-color: #041930; color: #e2ae76;">
      <div class="d-flex a ">
        <!-- Adjusted Icon (size and margin) -->
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             viewBox="0 0 512 512" xml:space="preserve" style="width: 30px; height: 30px; margin-right: 8px;">
            <g>
                <path style="fill:#FEE187;" d="M159.669,238.344L159.669,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
                    C207.835,216.779,186.269,238.344,159.669,238.344z"/>
                <path style="fill:#FEE187;" d="M352.331,238.344L352.331,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
                    C400.496,216.779,378.932,238.344,352.331,238.344z"/>
                <rect x="191.378" y="312.192" style="fill:#FEE187;" width="129.249" height="178.209"/>
            </g>
            <path style="fill:#FFC61B;" d="M496.828,104.985c8.379,0,15.172-6.792,15.172-15.172V58.537c0-28.728-23.372-52.099-52.099-52.099
                h-59.404h-96.332h-96.331h-96.332H52.099C23.372,6.437,0,29.809,0,58.537V190.18c0,20.106,9.428,38.04,24.084,49.651v250.563
                c0,8.379,6.792,15.172,15.172,15.172h152.122h129.244h152.124c8.379,0,15.172-6.792,15.172-15.172V312.189
                c0-8.379-6.792-15.172-15.172-15.172c-8.379,0-15.172,6.792-15.172,15.172v163.032h-121.78V312.189
                c0-8.379-6.792-15.172-15.172-15.172H191.378c-8.379,0-15.172,6.792-15.172,15.172v163.032H54.428V252.878
                c2.913,0.413,5.885,0.639,8.91,0.639c19.267,0,36.54-8.659,48.166-22.275c11.626,13.617,28.899,22.275,48.166,22.275
                s36.54-8.659,48.166-22.275c11.626,13.617,28.899,22.275,48.166,22.275s36.54-8.659,48.166-22.275
                c11.626,13.617,28.899,22.275,48.166,22.275c19.267,0,36.54-8.659,48.166-22.275c11.626,13.617,28.899,22.275,48.166,22.275
                c34.924,0,63.338-28.414,63.338-63.338v-26.232c0-8.379-6.792-15.172-15.172-15.172s-15.172,6.792-15.172,15.172v26.232
                c0,18.193-14.8,32.994-32.994,32.994s-32.994-14.8-32.994-32.994V36.78h44.232c11.996,0,21.755,9.76,21.755,21.755v31.277
                C481.656,98.193,488.449,104.985,496.828,104.985z M206.55,327.361h98.901v147.86H206.55V327.361z M63.338,223.173
                c-18.194,0-32.994-14.802-32.994-32.994V58.537c0-11.996,9.76-21.755,21.755-21.755h44.232V190.18
                C96.331,208.371,81.531,223.173,63.338,223.173z M159.669,223.173c-18.193,0-32.994-14.8-32.994-32.994V36.78h65.988v153.398
                C192.663,208.371,177.861,223.173,159.669,223.173z M255.999,223.173c-18.193,0-32.994-14.8-32.994-32.994V36.78h65.987v153.398
                C288.993,208.371,274.193,223.173,255.999,223.173z M352.331,223.173c-18.193,0-32.994-14.8-32.994-32.994V36.78h65.988v153.398
                C385.326,208.371,370.524,223.173,352.331,223.173z"/>
        </svg>

        <!-- Showcase Title and Date -->
        <h4 class="mb-0" style="color: #e2ae76; font-size: 14px;">
          Showcase: {{ $showcase->showcase_date->format('Y-m-d') }}
        </h4>
      </div>
    </div>

    <div class="card-body">
      <!-- Showcase details grid -->
      <div class="row row-cols-1 row-cols-md-2 g-4 mb-3" style="width: 50%">
        <div class="col">
         <strong> <p class="text-uppercase text-muted small mb-1" style="font-size: 20px;">Break-even (€)</p></strong>
          <p class="fs-5 fw-bold mb-0" style="font-size: 12px;">{{ number_format($showcase->break_even, 2) }}</p>
        </div>
        <div class="col">
          <strong> <p class="text-uppercase text-muted small mb-1" style="font-size: 20px;">Total Revenue (€)</p></strong>
          <p class="fs-5 fw-bold mb-0" style="font-size: 12px;">{{ number_format($showcase->total_revenue, 2) }}</p>
        </div>
      
        <div class="col">
          <strong> <p class="text-uppercase text-muted small mb-1" style="font-size: 20px;">Plus (€)</p></strong>
          <p class="fs-5 fw-bold mb-0" style="font-size: 12px;">{{ number_format($showcase->plus, 2) }}</p>
        </div>
        <div class="col">
          <strong> <p class="text-uppercase text-muted small mb-1" style="font-size: 20px;">Real Margin (%)</p></strong>
          <p class="fs-5 fw-bold mb-0" style="font-size: 12px;">
            @if($showcase->real_margin >= 0)
              <span class="text-success">{{ $showcase->real_margin }}%</span>
            @else
              <span class="text-danger">{{ $showcase->real_margin }}%</span>
            @endif
          </p>
        </div>
      
        <div class="col">
          <strong> <p class="text-uppercase text-muted small mb-1" style="font-size: 20px;">Last Updated</p></strong>
          <p class="fs-5 mb-0" style="font-size: 12px;">{{ optional($showcase->updated_at)->format('Y-m-d H:i') ?? '—' }}</p>
        </div>
      </div>
      

      <hr class="border-secondary" style="margin-top: 20px;">

      <!-- Showcase Recipes Breakdown Table -->
      <h5 class="mt-4" style="font-size: 16px;">Showcase Products Details</h5>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-light">
            <tr>
              <th style="font-size: 14px;">Recipe</th>
              <th style="font-size: 14px;">Department</th>
              <th style="font-size: 14px;">Price</th>
              <th style="font-size: 14px;">Quantity</th>
              <th style="font-size: 14px;">Sold</th>
              <th style="font-size: 14px;">Reuse</th>
              <th style="font-size: 14px;">Waste</th>
              <th style="font-size: 14px;">Potential Income</th>
              <th style="font-size: 14px;">Actual Revenue</th>
            </tr>
          </thead>
          <tbody>
            @php
              $total_sold = 0;
              $total_waste = 0;
              $total_quantity = 0;
              $total_reuse = 0;
              $total_potential_income = 0;
              $total_actual_revenue = 0;
            @endphp
            @foreach($showcase->recipes as $recipe)
              <tr>
                <td style="font-size: 14px;">{{ $recipe->recipe->recipe_name }}</td>
                <td style="font-size: 14px;">{{ $recipe->department->name ?? 'N/A' }}</td>
                <td style="font-size: 14px;">€{{ number_format($recipe->price, 2) }}</td>
                <td style="font-size: 14px;">{{ $recipe->quantity }}</td>
                <td style="font-size: 14px;">{{ $recipe->sold }}</td>
                <td style="font-size: 14px;">{{ $recipe->reuse }}</td>
                <td style="font-size: 14px;">{{ $recipe->waste }}</td>
                <td style="font-size: 14px;">€{{ number_format($recipe->potential_income, 2) }}</td>
                <td style="font-size: 14px;">€{{ number_format($recipe->actual_revenue, 2) }}</td>
              </tr>
              @php
                $total_sold += $recipe->sold;
                $total_waste += $recipe->waste;
                $total_quantity += $recipe->quantity;
                $total_reuse += $recipe->reuse;
                $total_potential_income += $recipe->potential_income;
                $total_actual_revenue += $recipe->actual_revenue;
              @endphp
            @endforeach
          </tbody>
          <tfoot>
            <tr class="table-warning">
              <td colspan="3" style="font-size: 14px;" class="text-end"><strong>Total:</strong></td>
              <td style="font-size: 14px;">{{ $total_quantity }}</td>
              <td style="font-size: 14px;">{{ $total_sold }}</td>
              <td style="font-size: 14px;">{{ $total_reuse }}</td>
              <td style="font-size: 14px;">{{ $total_waste }}</td>
              <td style="font-size: 14px;">€{{ number_format($total_potential_income, 2) }}</td>
              <td style="font-size: 14px;">€{{ number_format($total_actual_revenue, 2) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
