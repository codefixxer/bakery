 @extends('frontend.layouts.app')

 @section('title', 'Dashboard')

 @section('content')







     <div class="dashboard-main-body">

         <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
             <h6 class="fw-semibold mb-0">Dashboard</h6>
             <ul class="d-flex align-items-center gap-2">
                 <li class="fw-medium">
                     <a href="index.html" class="d-flex align-items-center gap-1 hover-text-primary">
                         <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                         Dashboard
                     </a>
                 </li>
                 <li>-</li>
                 <li class="fw-medium">CRM</li>
             </ul>
         </div>

         <div class="row gy-4">



















             {{-- resources/views/dashboard.blade.php --}}
             <div class="col-xxl-12">
                 <div class="row gy-4">

                     {{-- Total Users --}}
                     <div class="col-xxl-4 col-sm-6">
                         <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-1">
                             <div class="card-body p-0">
                                 <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                     <div class="d-flex align-items-center gap-2">
                                         <span
                                             class="mb-0 w-48-px h-48-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6">
                                             <iconify-icon icon="mingcute:user-follow-fill"></iconify-icon>
                                         </span>
                                         <div>
                                             <span class="mb-2 fw-medium text-secondary-light text-sm">Total Users</span>
                                             <h6 class="fw-semibold">{{ number_format($totalUsers) }}</h6>
                                         </div>
                                     </div>
                                     <div id="total-users-chart"></div>
                                 </div>
                                 <p class="text-sm mb-0">Since group creation</p>
                             </div>
                         </div>
                     </div>

                     {{-- Total Recipes --}}
                     <div class="col-xxl-4 col-sm-6">
                         <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-2">
                             <div class="card-body p-0">
                                 <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                     <div class="d-flex align-items-center gap-2">
                                         <span
                                             class="mb-0 w-48-px h-48-px bg-success-main flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6">
                                             <iconify-icon icon="uis:box" class="icon"></iconify-icon>
                                         </span>
                                         <div>
                                             <span class="mb-2 fw-medium text-secondary-light text-sm">Total Recipes</span>
                                             <h6 class="fw-semibold">{{ number_format($totalRecipes) }}</h6>
                                         </div>
                                     </div>
                                     <div id="total-recipes-chart"></div>
                                 </div>
                                 <p class="text-sm mb-0">Across all users in group</p>
                             </div>
                         </div>
                     </div>

                     {{-- Total Showcases --}}
                     <div class="col-xxl-4 col-sm-6">
                         <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-3">
                             <div class="card-body p-0">
                                 <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                     <div class="d-flex align-items-center gap-2">
                                         <span
                                             class="mb-0 w-48-px h-48-px bg-yellow text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                                             <iconify-icon icon="mdi:television-ambient-light"
                                                 class="icon"></iconify-icon>
                                         </span>
                                         <div>
                                             <span class="mb-2 fw-medium text-secondary-light text-sm">Total
                                                 Showcases</span>
                                             <h6 class="fw-semibold">{{ number_format($totalShowcases) }}</h6>
                                         </div>
                                     </div>
                                     <div id="total-showcase-chart"></div>
                                 </div>
                                 <p class="text-sm mb-0">All-time count</p>
                             </div>
                         </div>
                     </div>

  @can('Dashboard(Sales, Costs)')
                     <div class="col-xxl-4 col-sm-6">
                         <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-4">
                             <div class="card-body p-0">
                                 <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                     <div class="d-flex align-items-center gap-2">
                                         <span
                                             class="mb-0 w-48-px h-48-px bg-purple text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                                             <iconify-icon icon="iconamoon:discount-fill"></iconify-icon>
                                         </span>
                                         <div>
                                             <span class="mb-2 fw-medium text-secondary-light text-sm">Sales
                                                 ({{ $year }})</span>
                                             <h6 class="fw-semibold">${{ number_format($totalSaleThisYear, 2) }}</h6>
                                         </div>
                                     </div>
                                     <div id="total-sales-chart"></div>
                                 </div>
                                 <p class="text-sm mb-0">Year-to-date</p>
                             </div>
                         </div>
                     </div>
                     @endcan

                     {{-- Total Waste This Year --}}
                     <div class="col-xxl-4 col-sm-6">
                         <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-5">
                             <div class="card-body p-0">
                                 <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                     <div class="d-flex align-items-center gap-2">
                                         <span
                                             class="mb-0 w-48-px h-48-px bg-pink text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                                             <iconify-icon icon="fluent:trash-24-regular" class="icon"></iconify-icon>
                                         </span>
                                         <div>
                                             <span class="mb-2 fw-medium text-secondary-light text-sm">Waste
                                                 ({{ $year }})</span>
                                             <h6 class="fw-semibold">{{ number_format($totalWasteThisYear) }}</h6>
                                         </div>
                                     </div>
                                     <div id="total-waste-chart"></div>
                                 </div>
                                 <p class="text-sm mb-0">Year-to-date quantity</p>
                             </div>
                         </div>
                     </div>

                        @can('Dashboard(Sales, Costs)')

                     {{-- Total Profit This Year --}}
                     <div class="col-xxl-4 col-sm-6">
                         <div class="card p-3 shadow-2 radius-8 border input-form-light h-100 bg-gradient-end-6">
                             <div class="card-body p-0">
                                 <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                     <div class="d-flex align-items-center gap-2">
                                         <span
                                             class="mb-0 w-48-px h-48-px bg-cyan text-white flex-shrink-0 d-flex justify-content-center align-items-center rounded-circle h6">
                                             <iconify-icon icon="streamline:bag-dollar-solid" class="icon"></iconify-icon>
                                         </span>
                                         <div>
                                             <span class="mb-2 fw-medium text-secondary-light text-sm">Profit
                                                 ({{ $year }})</span>
                                             <h6 class="fw-semibold">${{ number_format($totalProfitThisYear, 2) }}</h6>
                                         </div>
                                     </div>
                                     <div id="total-profit-chart"></div>
                                 </div>
                                 <p class="text-sm mb-0">Year-to-date margin</p>
                             </div>
                         </div>
                     </div>

                     @endcan

                 </div>
             </div>
































  @can('Dashboard(Sales, Costs)')


             <div class="col-xxl-12">
                 <div class="card h-100 radius-8 border-0">
                     <div class="card-body p-24">

                         <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                             <div>
                                 <h6 class="mb-2 fw-bold text-lg">Earning Statistic</h6>
                                 <span class="text-sm fw-medium text-secondary-light">Monthly sales overview</span>
                             </div>

                             <div class="d-flex align-items-center gap-2">


                                 <input type="date" id="startDate" class="form-control form-control-sm" />
                                 <input type="date" id="endDate" class="form-control form-control-sm" />
                                 <button id="applyDateFilter" class="btn btn-sm btn-primary">Apply</button>
                             </div>
                         </div>

                         <div class="mt-20 d-flex justify-content-center flex-wrap gap-3">
                             <div
                                 class="d-inline-flex align-items-center gap-2 p-2 radius-8 border pe-36 br-hover-primary group-item">
                                 <span
                                     class="bg-neutral-100 w-44-px h-44-px text-xxl radius-8 d-flex justify-content-center align-items-center text-secondary-light group-hover:bg-primary-600 group-hover:text-white">
                                     <iconify-icon icon="fluent:cart-16-filled" class="icon"></iconify-icon>
                                 </span>
                                 <div>
                                     <span class="text-secondary-light text-sm fw-medium">Sales</span>
                                     <h6 class="text-md fw-semibold mb-0">${{ number_format($sales, 2) }}</h6>
                                 </div>
                             </div>

                             <div
                                 class="d-inline-flex align-items-center gap-2 p-2 radius-8 border pe-36 br-hover-primary group-item">
                                 <span
                                     class="bg-neutral-100 w-44-px h-44-px text-xxl radius-8 d-flex justify-content-center align-items-center text-secondary-light group-hover:bg-primary-600 group-hover:text-white">
                                     <iconify-icon icon="uis:chart" class="icon"></iconify-icon>
                                 </span>
                                 <div>
                                     <span class="text-secondary-light text-sm fw-medium">Plus</span>
                                     <h6 class="text-md fw-semibold mb-0">${{ number_format($plus, 2) }}</h6>
                                 </div>
                             </div>

                             <div
                                 class="d-inline-flex align-items-center gap-2 p-2 radius-8 border pe-36 br-hover-primary group-item">
                                 <span
                                     class="bg-neutral-100 w-44-px h-44-px text-xxl radius-8 d-flex justify-content-center align-items-center text-secondary-light group-hover:bg-primary-600 group-hover:text-white">
                                     <iconify-icon icon="ph:arrow-fat-up-fill" class="icon"></iconify-icon>
                                 </span>
                                 <div>
                                     <span class="text-secondary-light text-sm fw-medium">Profit</span>
                                     <h6 class="text-md fw-semibold mb-0">${{ number_format($realMargin, 2) }}</h6>
                                 </div>
                             </div>
                         </div>

                         <div class="mt-4">
                             <div id="barChart"></div>
                         </div>

                     </div>
                 </div>
             </div>






             <div class="col-xxl-4">
                 <div class="card h-100 radius-8 border-0">
                     <div class="card-body p-24">

                         {!! $comparisonChart->container() !!}
                     </div>
                 </div>
             </div>


             <div class="col-xxl-4 mb-4">
                 <div class="card h-100 radius-8 border-0">
                     <div class="card-body p-24">

                         {!! $yearlyCostChart->container() !!}
                     </div>
                 </div>
             </div>

             <div class="col-xxl-4 mb-4">
                 <div class="card h-100 radius-8 border-0">
                     <div class="card-body p-24">
                         {!! $yearlyIncomeChart->container() !!}
                     </div>
                 </div>
             </div>

             @endcan

















<div class="col-xxl-6 mb-4">
        <div class="card h-100">
          <div
            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="text-lg fw-semibold mb-0">Top 5 Sold Products</h6>
            <div class="d-flex align-items-center gap-2">
              <input type="date" id="soldStart" class="form-control form-control-sm" />
              <input type="date" id="soldEnd" class="form-control form-control-sm" />
              <button id="soldFilter" class="btn btn-sm btn-primary">Apply</button>
            </div>
          </div>
          <div class="card-body p-24">
            <div class="table-responsive scroll-sm mb-4">
              <table class="table bordered-table mb-0" id="soldTable">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th class="text-end">Quantity Sold</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($topSold as $item)
                    <tr>
                      <td>{{ $item->recipe->recipe_name }}</td>
                      <td class="text-end">{{ $item->sold }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div id="soldPie"></div>
          </div>
        </div>
      </div>

      {{-- Top 5 Wasted Products --}}
      <div class="col-xxl-6 mb-4">
        <div class="card h-100">
          <div
            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center justify-content-between">
            <h6 class="text-lg fw-semibold mb-0">Top 5 Wasted Products</h6>
            <div class="d-flex align-items-center gap-2">
              <input type="date" id="wastedStart" class="form-control form-control-sm" />
              <input type="date" id="wastedEnd" class="form-control form-control-sm" />
              <button id="wastedFilter" class="btn btn-sm btn-primary">Apply</button>
            </div>
          </div>
          <div class="card-body p-24">
            <div class="table-responsive scroll-sm mb-4">
              <table class="table bordered-table mb-0" id="wastedTable">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th class="text-end">Quantity Wasted</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($topWasted as $item)
                    <tr>
                      <td>{{ $item->recipe->recipe_name }}</td>
                      <td class="text-end">{{ $item->waste }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div id="wastedPie"></div>
          </div>
        </div>
      </div>
    </div>


































             {{-- resources/views/dashboard.blade.php --}}
             <div class="row gy-4 mb-4">
                 {{-- Return vs Used --}}
                 <div class="col-xxl-4 col-sm-6">
                     <div class="card h-100 radius-8 border-0">
                         <div class="card-body p-24">
                             <h6 class="text-lg fw-semibold mb-3">Return vs Used</h6>
                             <p>Total Supplied: {{ number_format($totalSupplied) }}</p>
                             <p>Total Returned: {{ number_format($totalReturned) }}</p>
                             {!! $returnRateChart->container() !!}
                         </div>
                     </div>
                 </div>

                 {{-- Production by Chef --}}
                 <div class="col-xxl-4 col-sm-6">
                     <div class="card h-100 radius-8 border-0">
                         <div class="card-body p-24">
                             <h6 class="text-lg fw-semibold mb-3">Production by Chef</h6>
                             {!! $chefChart->container() !!}
                         </div>
                     </div>
                 </div>

                 <div class="col-xxl-4 col-sm-6">
                     <div class="card h-100 radius-8 border-0">
                         <div class="card-body p-24">
                             <h6 class="text-lg fw-semibold mb-3">Costs by Category</h6>
                             {!! $costCategoryChart->container() !!}
                         </div>
                     </div>
                 </div>

                 {{-- Production vs Waste Trend --}}
                 <div class="col-xxl-12">
                     <div class="card h-100 radius-8 border-0">
                         <div class="card-body p-24">
                             <h6 class="text-lg fw-semibold mb-3">Production vs Waste</h6>
                             {!! $prodWasteChart->container() !!}
                         </div>
                     </div>
                 </div>
             </div>



         </div>
     </div>


 @endsection


 @section('scripts')
     {{-- <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script> --}}
     <script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
     <script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
     <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
     <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
     <script src="{{ asset('assets/js/lib/prism.js') }}"></script>
     <script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
     <script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script>
     <script src="{{ asset('assets/js/homeTwoChart.js') }}"></script>

     <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
     {!! $chart->script() !!}


     {!! $comparisonChart->script() !!}

     {!! $yearlyCostChart->script() !!}
     {!! $yearlyIncomeChart->script() !!}
     {!! $soldPieChart->script() !!}
     {!! $wastedPieChart->script() !!}
     {!! $returnRateChart->script() !!}
     {!! $chefChart->script() !!}
     {!! $prodWasteChart->script() !!}
     {!! $costCategoryChart->script() !!}



     <script>
         document.addEventListener('DOMContentLoaded', function() {
             // fullMonthlyData passed from controller
             const fullData = @json($fullMonthlyData);

             function monthLabel(d) {
                 const dt = new Date(d);
                 return dt.toLocaleString('default', {
                     month: 'short'
                 });
             }

             // Initial chart data
             let labels = fullData.map(i => monthLabel(i.date));
             let values = fullData.map(i => i.total);

             const options = {
                 series: [{
                     name: 'Earnings',
                     data: values
                 }],
                 chart: {
                     type: 'bar',
                     height: 350
                 },
                 xaxis: {
                     categories: labels
                 }
             };
             const chart = new ApexCharts(document.querySelector('#barChart'), options);
             chart.render();

             // Date-range filter
             const startInput = document.getElementById('startDate');
             const endInput = document.getElementById('endDate');
             const applyBtn = document.getElementById('applyDateFilter');

             applyBtn.addEventListener('click', () => {
                 const start = startInput.value;
                 const end = endInput.value;
                 if (!start || !end) return;

                 const filtered = fullData.filter(i => i.date >= start && i.date <= end);
                 const newLabels = filtered.map(i => monthLabel(i.date));
                 const newValues = filtered.map(i => i.total);

                 chart.updateOptions({
                     xaxis: {
                         categories: newLabels
                     }
                 });
                 chart.updateSeries([{
                     name: 'Earnings',
                     data: newValues
                 }]);
             });
         });
     </script>

     <script>
    document.addEventListener('DOMContentLoaded', function() {
      // full data arrays passed from controller
      const fullSoldData   = @json($fullSoldData);
      const fullWastedData = @json($fullWastedData);

      // helper: aggregate & take top 5
      function aggregateTop(data, valueKey) {
        const agg = {};
        data.forEach(item => {
          agg[item.recipe_name] = (agg[item.recipe_name] || 0) + item[valueKey];
        });
        return Object.entries(agg)
          .map(([name, val]) => ({ name, val }))
          .sort((a, b) => b.val - a.val)
          .slice(0, 5);
      }

      // initialize Sold chart
      let soldTop = aggregateTop(fullSoldData, 'sold');
      const soldOptions = {
        chart: { type: 'donut', height: 250 },
        series: soldTop.map(i => i.val),
        labels: soldTop.map(i => i.name)
      };
      const soldChart = new ApexCharts(document.querySelector('#soldPie'), soldOptions);
      soldChart.render();

      // initialize Wasted chart
      let wastedTop = aggregateTop(fullWastedData, 'waste');
      const wastedOptions = {
        chart: { type: 'donut', height: 250 },
        series: wastedTop.map(i => i.val),
        labels: wastedTop.map(i => i.name)
      };
      const wastedChart = new ApexCharts(document.querySelector('#wastedPie'), wastedOptions);
      wastedChart.render();

      // filter & update function
      function applyFilter(fullData, startId, endId, tableSelector, chart, valueKey) {
        const start = document.getElementById(startId).value;
        const end   = document.getElementById(endId).value;
        if (!start || !end) return;
        const filtered = fullData.filter(i => i.date >= start && i.date <= end);
        const top5 = aggregateTop(filtered, valueKey);

        // update table
        const tbody = document.querySelector(`${tableSelector} tbody`);
        tbody.innerHTML = top5.map(i =>
          `<tr><td>${i.name}</td><td class="text-end">${i.val}</td></tr>`
        ).join('');

        // update chart
        chart.updateOptions({ labels: top5.map(i => i.name) });
        chart.updateSeries(top5.map(i => i.val));
      }

      document.getElementById('soldFilter').addEventListener('click', () =>
        applyFilter(fullSoldData, 'soldStart', 'soldEnd', '#soldTable', soldChart, 'sold')
      );
      document.getElementById('wastedFilter').addEventListener('click', () =>
        applyFilter(fullWastedData, 'wastedStart', 'wastedEnd', '#wastedTable', wastedChart, 'waste')
      );
    });
  </script>
 @endsection
