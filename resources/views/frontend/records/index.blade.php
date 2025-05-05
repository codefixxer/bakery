@extends('frontend.layouts.app')

@section('title', 'Filter Records')

@section('content')
@php
    $allCategories = $categories->pluck('name');
    $allDepartments = $departments->pluck('name');
@endphp

<div class="container py-5 px-md-5">

  {{-- Page Heading --}}
  <div class="text-center py-3 px-3 rounded mb-5 shadow" style="background-color: #041930;">
    <h2 class="fw-bold mb-0" style="color: #e2ae76; font-size: 1.8rem;">
      Showcase &amp; External Supply Records
    </h2>
  </div>

  {{-- Filter Form --}}
  <div class="card mb-5 shadow-sm border-0">
    <div class="card-body">
      <div class="row g-4">
        <div class="col-md-3">
          <label class="form-label fw-semibold">From</label>
          <input type="date" id="filter_from" class="form-control" value="{{ $from }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">To</label>
          <input type="date" id="filter_to" class="form-control" value="{{ $to }}">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Recipe Name</label>
          <input type="text" id="filter_recipe" class="form-control" placeholder="Enter recipe...">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Category</label>
          <select id="filter_category" class="form-select">
            <option value="">All Categories</option>
            @foreach($allCategories as $cat)
              <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Department</label>
          <select id="filter_department" class="form-select">
            <option value="">All Departments</option>
            @foreach($allDepartments as $dept)
              <option value="{{ strtolower($dept) }}">{{ $dept }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  {{-- No Records Alert --}}
  <div id="noRecords" class="alert alert-info text-center" style="display:none">
    No records found for the selected filters.
  </div>

  {{-- Summary Cards --}}
  <div id="summary" class="row justify-content-center mb-5 g-4" style="display:none">
    <div class="col-sm-8 col-md-5 col-lg-4">
      <div class="card border-0 shadow h-100">
        <div class="card-body text-center">
          <i class="bi bi-graph-up display-4 text-primary mb-3"></i>
          <h5 class="card-title">Total Showcase Revenue</h5>
          <p class="display-5 fw-bold mb-1" id="totalShowRevenue">0.00</p>
          <small class="text-muted fw-semibold fs-5" id="pctShow">0%</small>
        </div>
      </div>
    </div>
    <div class="col-sm-8 col-md-5 col-lg-4">
      <div class="card border-0 shadow h-100">
        <div class="card-body text-center">
          <i class="bi bi-currency-dollar display-4 text-danger mb-3"></i>
          <h5 class="card-title">Total External Cost</h5>
          <p class="display-5 fw-bold mb-1" id="totalExternalCost">0.00</p>
          <small class="text-muted fw-semibold fs-5" id="pctExt">0%</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Tables --}}
  <div class="row gx-4 gy-5">
    {{-- Showcase Table --}}
    <div class="col-lg-6">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-header d-flex align-items-center" style="background-color: #041930; color: #e2ae76; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
          <i class="bi bi-list-ul me-2" style="color: #e2ae76;"></i>
          <strong class="fs-5">Showcase Records</strong>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle">
              <thead class="table-light text-center">
                <tr>
                  <th>Date</th>
                  <th class="text-start">Recipe</th>
                  <th>Qty</th>
                  <th>Sold</th>
                  <th>Reuse</th>
                  <th>Waste</th>
                  <th>Revenue</th>
                </tr>
              </thead>
              <tbody id="showcaseBody" class="text-center"></tbody>
              <tfoot class="table-light text-center">
                <tr>
                  <th colspan="2" class="text-end">Grand Total:</th>
                  <th id="showcaseQtyFooter">0</th>
                  <th id="showcaseSoldFooter">0</th>
                  <th id="showcaseReuseFooter">0</th>
                  <th id="showcaseWasteFooter">0</th>
                  <th id="showcaseFooter">0.00</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- External Supply Table --}}
    <div class="col-lg-6">
      <div class="card shadow-sm h-100 border-0">
        <div class="card-header d-flex align-items-center" style="background-color: #041930; color: #e2ae76; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
          <i class="bi bi-box-seam me-2" style="color: #e2ae76;"></i>
          <strong class="fs-5">External Supply Records</strong>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 align-middle">
              <thead class="table-light text-center">
                <tr>
                  <th>Date</th>
                  <th class="text-start">Client</th>
                  <th class="text-start">Recipe</th>
                  <th>Returns</th>
                  <th>Qty</th>
                  <th>Total ($)</th>
                </tr>
              </thead>
              <tbody id="externalBody" class="text-center"></tbody>
              <tfoot class="table-light text-center">
                <tr>
                  <th colspan="3" class="text-end">Grand Total:</th>
                  <th id="externalReturnsFooter">0</th>
                  <th id="externalQtyFooter">0</th>
                  <th id="externalFooter">0.00</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection





@section('scripts')
<script>
  @php
    // include category + department in the JS payload
    $showData = $showcaseRecords->flatMap(fn($sc) =>
      $sc->recipes->map(fn($line) => [
        'date'        => $sc->showcase_date->format('Y-m-d'),
        'recipe_name' => $line->recipe->recipe_name,
        'quantity'    => $line->quantity,
        'sold'        => $line->sold,
        'reuse'       => $line->reuse,
        'waste'       => $line->waste,
        'revenue'     => $line->actual_revenue,
        'category'    => optional($line->recipe->category)->name,
        'department'  => optional($line->recipe->department)->name,
      ])
    )->all();

    $extData = $externalRecords->flatMap(fn($s) =>
      $s->recipes->map(fn($line) => [
        'date'        => $s->supply_date->format('Y-m-d'),
        'client'      => $s->client->name,
        'recipe_name' => $line->recipe->recipe_name,
        'returns'     => $s->returnedGoods
                            ->flatMap(fn($rg) => $rg->recipes)
                            ->where('external_supply_recipe_id', $line->id)
                            ->sum('qty'),
        'qty'         => $line->qty,
        'total'       => $line->total_amount,
        'category'    => optional($line->recipe->category)->name,
        'department'  => optional($line->recipe->department)->name,
      ])
    )->all();
  @endphp

  const showcaseData  = {!! json_encode($showData) !!};
  const externalData  = {!! json_encode($extData) !!};

  function render() {
    const fromVal      = document.getElementById('filter_from').value;
    const toVal        = document.getElementById('filter_to').value;
    const recipeVal    = document.getElementById('filter_recipe').value.trim().toLowerCase();
    const categoryVal  = document.getElementById('filter_category').value;
    const departmentVal= document.getElementById('filter_department').value;

    const inRange = (d,a,b) => (!a||d>=a) && (!b||d<=b);
    const matchText = (hay, needle) => !needle || hay.toLowerCase().includes(needle);
    const matchExact = (hay, needle) => !needle || hay === needle;

    const fShow = showcaseData.filter(r =>
         inRange(r.date, fromVal, toVal)
      && matchText(r.recipe_name, recipeVal)
      && matchExact((r.category||'').toLowerCase(), categoryVal)
      && matchExact((r.department||'').toLowerCase(), departmentVal)
    );
    const fExt  = externalData.filter(r =>
         inRange(r.date, fromVal, toVal)
      && matchText(r.recipe_name, recipeVal)
      && matchExact((r.category||'').toLowerCase(), categoryVal)
      && matchExact((r.department||'').toLowerCase(), departmentVal)
    );

    const has = fShow.length || fExt.length;
    document.getElementById('noRecords').style.display        = has ? 'none':''; 
    document.getElementById('summary').style.display          = has ? 'flex':''; 
    document.getElementById('addIncomeContainer').style.display = has ? 'flex':''; 

    function groupByDate(arr) {
      return arr.reduce((a,r)=>{
        (a[r.date]||(a[r.date]={ date:r.date, items:[], sums:{}})).items.push(r);
        return a;
      },{});
    }

    function summarizeShow(g){
      g.sums = g.items.reduce((S,r)=>{
        S.quantity=(S.quantity||0)+ +r.quantity;
        S.sold    =(S.sold    ||0)+ +r.sold;
        S.reuse   =(S.reuse   ||0)+ +r.reuse;
        S.waste   =(S.waste   ||0)+ +r.waste;
        S.revenue =(S.revenue ||0)+ +r.revenue;
        return S;
      },{});
    }

    function summarizeExt(g){
      g.sums = g.items.reduce((S,r)=>{
        const unitPrice = r.qty > 0 ? (r.total / r.qty) : 0;
        const returnValue = r.returns * unitPrice;

        S.returns = (S.returns || 0) + +r.returns;
        S.qty     = (S.qty     || 0) + +r.qty;
        S.total   = (S.total   || 0) + (+r.total - returnValue);
        return S;
      },{});
    }

    const sGroups = Object.values(groupByDate(fShow));
    sGroups.forEach(summarizeShow);
    const eGroups = Object.values(groupByDate(fExt));
    eGroups.forEach(summarizeExt);

    window.lastShowGroups = sGroups.map(g=>({date:g.date,amount:g.sums.revenue}));
    window.lastExtGroups  = eGroups.map(g=>({date:g.date,amount:g.sums.total}));

    const grandShow = sGroups.reduce((sum,g)=>sum+g.sums.revenue,0);
    const grandExt  = eGroups.reduce((sum,g)=>sum+g.sums.total,0);
    const gross     = grandShow+grandExt;
    document.getElementById('totalShowRevenue').textContent   = grandShow.toFixed(2);
    document.getElementById('pctShow').textContent           = gross?((grandShow/gross)*100).toFixed(0)+'%':'0%';
    document.getElementById('totalExternalCost').textContent = grandExt.toFixed(2);
    document.getElementById('pctExt').textContent            = gross?((grandExt/gross)*100).toFixed(0)+'%':'0%';

    // Render showcase
    let outS = '';
    sGroups.forEach(g=>{
      outS+=`
      <tr class="group-header" data-date="${g.date}">
        <td colspan="2" class="text-start">
          <i class="bi bi-caret-right-fill toggle-icon"></i>
          ${g.date} (${g.items.length} lines)
        </td>
        <td>${g.sums.quantity}</td>
        <td>${g.sums.sold}</td>
        <td>${g.sums.reuse}</td>
        <td>${g.sums.waste}</td>
        <td>${g.sums.revenue.toFixed(2)}</td>
      </tr>`;
      g.items.forEach(r=>{
        outS+=`
        <tr class="group-child group-${g.date}" style="display:none">
          <td>${r.date}</td>
          <td class="text-start">${r.recipe_name}</td>
          <td>${r.quantity}</td>
          <td>${r.sold}</td>
          <td>${r.reuse}</td>
          <td>${r.waste}</td>
          <td>${(+r.revenue).toFixed(2)}</td>
        </tr>`;
      });
    });
    document.getElementById('showcaseBody').innerHTML = outS;
    document.getElementById('showcaseQtyFooter').textContent   = sGroups.reduce((s,g)=>s+g.sums.quantity,0);
    document.getElementById('showcaseSoldFooter').textContent  = sGroups.reduce((s,g)=>s+g.sums.sold,0);
    document.getElementById('showcaseReuseFooter').textContent = sGroups.reduce((s,g)=>s+g.sums.reuse,0);
    document.getElementById('showcaseWasteFooter').textContent = sGroups.reduce((s,g)=>s+g.sums.waste,0);
    document.getElementById('showcaseFooter').textContent      = grandShow.toFixed(2);

    // Render external table
    let outE = '';
    eGroups.forEach(g=>{
      outE+=`
      <tr class="group-header" data-date="${g.date}">
        <td colspan="3" class="text-start">
          <i class="bi bi-caret-right-fill toggle-icon"></i>
          ${g.date} (${g.items.length} lines)
        </td>
        <td>${g.sums.returns}</td>
        <td>${g.sums.qty}</td>
        <td>${g.sums.total.toFixed(2)}</td>
      </tr>`;
      g.items.forEach(r=>{
        outE+=`
        <tr class="group-child group-${g.date}" style="display:none">
          <td>${r.date}</td>
          <td class="text-start">${r.client}</td>
          <td class="text-start">${r.recipe_name}</td>
          <td>${r.returns}</td>
          <td>${r.qty}</td>
          <td>${(+r.total).toFixed(2)}</td>
        </tr>`;
      });
    });
    document.getElementById('externalBody').innerHTML          = outE;
    document.getElementById('externalReturnsFooter').textContent = eGroups.reduce((s,g)=>s+g.sums.returns,0);
    document.getElementById('externalQtyFooter').textContent     = eGroups.reduce((s,g)=>s+g.sums.qty,0);
    document.getElementById('externalFooter').textContent        = grandExt.toFixed(2);

    // Expand/collapse logic
    document.querySelectorAll('.group-header').forEach(row=>{
      row.querySelector('.toggle-icon').onclick = () => {
        const date = row.dataset.date;
        const kids = document.querySelectorAll(`.group-${date}`);
        const show = kids[0].style.display==='none';
        kids.forEach(tr=>tr.style.display= show?'':'none');
        row.querySelector('.toggle-icon')
           .classList.toggle('bi-caret-down-fill', show);
        row.querySelector('.toggle-icon')
           .classList.toggle('bi-caret-right-fill', !show);
      };
    });
  }

  function debounce(fn,ms=200){
    let t;
    return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a),ms) };
  }

  document.getElementById('filter_from') .addEventListener('change', render);
  document.getElementById('filter_to')   .addEventListener('change', render);
  document.getElementById('filter_recipe').addEventListener('input', debounce(render));
  document.getElementById('filter_category')  .addEventListener('change', render);
  document.getElementById('filter_department').addEventListener('change', render);

  render();

  // POST to income
  document.getElementById('addToIncomeBtn').addEventListener('click', () => {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("income.addFiltered") }}';
    form.style.display = 'none';
    const token = document.createElement('input');
    token.name  = '_token';
    token.value = document.querySelector('meta[name="csrf-token"]').content;
    form.appendChild(token);
    window.lastShowGroups.forEach((g,i)=>{
      ['date','amount'].forEach(k=>{
        const inp = document.createElement('input');
        inp.name  = `showcase[${i}][${k}]`;
        inp.value = g[k];
        form.appendChild(inp);
      });
    });
    window.lastExtGroups.forEach((g,i)=>{
      ['date','amount'].forEach(k=>{
        const inp = document.createElement('input');
        inp.name  = `external[${i}][${k}]`;
        inp.value = g[k];
        form.appendChild(inp);
      });
    });
    document.body.appendChild(form);
    form.submit();
  });
</script>
@endsection

