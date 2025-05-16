@extends('frontend.layouts.app')

@section('title', 'Showcase & External Supply Records')

@section('content')
<div class="container py-5">
  {{-- Page Title --}}
  <div class="text-center mb-4">
    <h2 class="d-inline-block px-4 py-2" style="background:#041930; color:#e2ae76; border-radius:.5rem;">
      Showcase &amp; External Supply Records
    </h2>
  </div>

  {{-- Filters --}}
  <div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label">From</label>
          <input id="filter-from" type="date" value="{{ $from }}" class="form-control">
        </div>
        <div class="col-md-2">
          <label class="form-label">To</label>
          <input id="filter-to" type="date" value="{{ $to }}" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label">Recipe Name</label>
          <input id="filter-recipe" type="text" class="form-control" placeholder="Enter recipe…">
        </div>
        <div class="col-md-2">
          <label class="form-label">Category</label>
          <select id="filter-category" class="form-select">
            <option value="">All Categories</option>
            @foreach($showcaseGroups->flatten(1)->pluck('recipes.*.recipe.category.name')->flatten()->unique() as $cat)
              @if($cat)
                <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
              @endif
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Department</label>
          <select id="filter-department" class="form-select">
            <option value="">All Departments</option>
            @foreach($showcaseGroups->flatten(1)->pluck('recipes.*.recipe.department.name')->flatten()->unique() as $dept)
              @if($dept)
                <option value="{{ strtolower($dept) }}">{{ $dept }}</option>
              @endif
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>

  {{-- Summaries --}}
  <div class="row mb-5 gx-4">
    <div class="col-md-6">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <i class="bi bi-graph-up display-4 text-primary mb-2"></i>
          <h5>Total Showcase Revenue</h5>
          <p id="summary-showcase" class="display-6 mb-1">{{ number_format($totalShowcaseRevenue,2) }}</p>
          <small id="summary-showcase-pct" class="text-muted">0%</small>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">
          <i class="bi bi-currency-dollar display-4 text-danger mb-2"></i>
          <h5>Total External Cost</h5>
          <p id="summary-external" class="display-6 mb-1">{{ number_format($totalExternalCost,2) }}</p>
          <small id="summary-external-pct" class="text-muted">0%</small>
        </div>
      </div>
    </div>
  </div>

  <div class="row gx-4">
    {{-- Showcase (50%) --}}
    <div class="col-lg-6 mb-5">
      <div class="d-flex align-items-center"
     style="background: #041930;
            color: #e2ae76;
            padding: .5rem;
            border-top-left-radius: .5rem;
            border-top-right-radius: .5rem;">
  <svg
    class="me-1"
    viewBox="0 0 512.005 512.005"
    style="width: 1.0em;
           height: 1.0em;
           color: #e2ae76;"
    xmlns="http://www.w3.org/2000/svg">
    <g>
      <path fill="currentColor" d="M159.669,238.344L159.669,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
          C207.835,216.779,186.269,238.344,159.669,238.344z"/>
      <path fill="currentColor" d="M352.331,238.344L352.331,238.344c-26.601,0-48.166-21.564-48.166-48.166V21.609h96.331v168.57
          C400.496,216.779,378.932,238.344,352.331,238.344z"/>
      <rect fill="currentColor" x="191.378" y="312.192" width="129.249" height="178.209"/>
    </g>
    <path fill="currentColor" d="M496.828,104.985c8.379,0,15.172-6.792,15.172-15.172V58.537c0-28.728-23.372-52.099-52.099-52.099
        h-59.404h-96.332h-96.331h-96.332H52.099C23.372,6.437,0,29.809,0,58.537V190.18c0,20.106,9.428,38.04,24.084,49.651v250.563
        c0,8.379,6.792,15.172,15.172,15.172h152.122h129.244h152.124c8.379,0,15.172-6.792,15.172-15.172V312.189
        c0-8.379-6.792-15.172-15.172-15.172c-8.379,0-15.172,6.792-15.172,15.172v163.032h-121.78V312.189
        c0-8.379-6.792-15.172-15.172-15.172H191.378c-8.379,0-15.172,6.792-15.172,15.172v163.032H54.428V252.878
        c2.913,0.413,5.885,0.639,8.91,0.639c19.267,0,36.54-8.659,48.166-22.275c11.626,13.617,28.899,22.275,48.166,22.275
        s36.54-8.659,48.166-22.275c11.626,13.617,28.899,22.275,48.166,22.275s36.54-8.659,48.166-22.275
        c11.626,13.617,28.899,22.275,48.166,22.275c19.267,0,36.54-8.659,48.166-22.275c11.626,13.617,28.899,22.275,48.166,22.275
        c34.924,0,63.338-28.414,63.338-63.338v-26.232c0-8.379-6.792-15.172-15.172-15.172s-15.172,6.792-15.172,15.172v26.232
        c0,18.193-14.8,32.994-32.994,32.994s-32.994-14.8-32.994-32.994V36.78h44.232c11.996,0,21.755,9.76,21.755,21.755v31.277
        C481.656,98.193,488.449,104.985,496.828,104.985z"/>
  </svg>
  Showcase Records
</div>

      <table class="table mb-0 border showcaseTable">
        <thead class="table-light text-center">
          <tr>
            <th style="width:1%"></th>
            <th>Date</th>
            <th>Recipe</th>
            <th>Qty</th>
            <th>Sold</th>
            <th>Reuse</th>
            <th>Waste</th>
            <th class="text-end">Revenue (€)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($showcaseGroups as $date => $group)
            @php
              $lines = $group->flatMap(fn($sc)=> $sc->recipes);
              $sum   = $lines->sum('actual_revenue');
            @endphp
            <tr class="bg-light group-header text-center" data-date="{{ $date }}">
              <td class="toggle-arrow" style="cursor:pointer">
                <i class="bi bi-caret-right-fill"></i>
              </td>
              <td colspan="6" class="text-start">{{ $date }} ({{ $lines->count() }} lines)</td>
              <td class="text-end fw-semibold">{{ number_format($sum,2) }}</td>
            </tr>
            @foreach($group as $sc)
              @foreach($sc->recipes as $line)
                <tr class="group-{{ $date }} d-none text-center"
                    data-date="{{ $date }}"
                    data-recipe="{{ strtolower($line->recipe->recipe_name) }}"
                    data-category="{{ strtolower($line->recipe->category->name ?? '') }}"
                    data-department="{{ strtolower($line->recipe->department->name ?? '') }}"
                    data-qty="{{ $line->quantity }}"
                    data-sold="{{ $line->sold }}"
                    data-reuse="{{ $line->reuse }}"
                    data-waste="{{ $line->waste }}"
                    data-revenue="{{ $line->actual_revenue }}">
                  <td></td>
                  <td>{{ $sc->showcase_date->format('Y-m-d') }}</td>
                  <td>{{ $line->recipe->recipe_name }}</td>
                  <td>{{ $line->quantity }}</td>
                  <td>{{ $line->sold }}</td>
                  <td>{{ $line->reuse }}</td>
                  <td>{{ $line->waste }}</td>
                  <td class="text-end">{{ number_format($line->actual_revenue,2) }}</td>
                </tr>
              @endforeach
            @endforeach
          @endforeach
        </tbody>
        <tfoot class="table-light text-center">
          <tr>
            <th colspan="3" class="text-end">Grand Total:</th>
            <th id="showcaseQtyFooter">0</th>
            <th id="showcaseSoldFooter">0</th>
            <th id="showcaseReuseFooter">0</th>
            <th id="showcaseWasteFooter">0</th>
            <th id="showcaseFooter">0.00</th>
          </tr>
        </tfoot>
      </table>
    </div>

    {{-- External (50%) --}}
    <div class="col-lg-6 mb-5">
      <div class="d-flex align-items-center"
     style="background: #041930;
            color: #e2ae76;
            padding: .5rem;
            border-top-left-radius: .5rem;
            border-top-right-radius: .5rem;">
  <iconify-icon
    icon="mdi:warehouse"
    class="me-1"
    style="
           height: 1.2em;
           font-size:1.0vw;
           color: #e2ae76;">
  </iconify-icon>
  External Supply Records
</div>

      <table class="table mb-0 border externalTable">
        <thead class="table-light text-center">
          <tr>
            <th style="width:1%"></th>
            <th>Date</th>
            <th>Client</th>
            <th>Recipe</th>
            <th>Returns</th>
            <th>Qty</th>
            <th class="text-end">Total (€)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($externalGroups as $date => $group)
            @php
              $lines = $group->flatMap(fn($es)=> $es->recipes);
              $sum   = $lines->sum(function($line){
                $unit     = $line->qty>0?($line->total_amount/$line->qty):0;
                $returned = $line->returns->sum('qty') * $unit;
                return $line->total_amount - $returned;
              });
            @endphp
            <tr class="bg-light group-header text-center" data-date="{{ $date }}">
              <td class="toggle-arrow" style="cursor:pointer">
                <i class="bi bi-caret-right-fill"></i>
              </td>
              <td colspan="5" class="text-start">{{ $date }} ({{ $lines->count() }} lines)</td>
              <td class="text-end fw-semibold">{{ number_format($sum,2) }}</td>
            </tr>
            @foreach($group as $es)
              @foreach($es->recipes as $line)
                @php
                  $unit     = $line->qty>0?($line->total_amount/$line->qty):0;
                  $rQty     = $line->returns->sum('qty');
                  $netTotal = $line->total_amount - $rQty * $unit;
                @endphp
                <tr class="group-{{ $date }} d-none text-center"
                    data-date="{{ $date }}"
                    data-recipe="{{ strtolower($line->recipe->recipe_name) }}"
                    data-category="{{ strtolower($line->recipe->category->name ?? '') }}"
                    data-department="{{ strtolower($line->recipe->department->name ?? '') }}"
                    data-returns="{{ $rQty }}"
                    data-qty="{{ $line->qty }}"
                    data-total="{{ $netTotal }}">
                  <td></td>
                  <td>{{ $es->supply_date->format('Y-m-d') }}</td>
                  <td>{{ $es->client->name }}</td>
                  <td>{{ $line->recipe->recipe_name }}</td>
                  <td>{{ $rQty }}</td>
                  <td>{{ $line->qty }}</td>
                  <td class="text-end">{{ number_format($netTotal,2) }}</td>
                </tr>
              @endforeach
            @endforeach
          @endforeach
        </tbody>
        <tfoot class="table-light text-center">
          <tr>
            <th colspan="4" class="text-end">Grand Total:</th>
            <th id="externalReturnsFooter">0</th>
            <th id="externalQtyFooter">0</th>
            <th id="externalFooter">0.00</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const fromIn     = document.getElementById('filter-from');
  const toIn       = document.getElementById('filter-to');
  const recIn      = document.getElementById('filter-recipe');
  const catIn      = document.getElementById('filter-category');
  const deptIn     = document.getElementById('filter-department');

  const sumShowEl      = document.getElementById('summary-showcase');
  const pctShowEl      = document.getElementById('summary-showcase-pct');
  const sumExtEl       = document.getElementById('summary-external');
  const pctExtEl       = document.getElementById('summary-external-pct');
  const footerShowEl   = document.getElementById('showcaseFooter');
  const qtyShowEl      = document.getElementById('showcaseQtyFooter');
  const soldShowEl     = document.getElementById('showcaseSoldFooter');
  const reuseShowEl    = document.getElementById('showcaseReuseFooter');
  const wasteShowEl    = document.getElementById('showcaseWasteFooter');
  const footerExtEl    = document.getElementById('externalFooter');
  const retExtEl       = document.getElementById('externalReturnsFooter');
  const qtyExtEl       = document.getElementById('externalQtyFooter');

  function applyFilter() {
    const f = val => !val || val.trim()==='';
    const from  = fromIn.value;
    const to    = toIn.value;
    const rf    = recIn.value.trim().toLowerCase();
    const cf    = catIn.value.trim().toLowerCase();
    const df    = deptIn.value.trim().toLowerCase();

    let showSum=0, extSum=0;
    let qtySum=0, soldSum=0, reuseSum=0, wasteSum=0;
    let retSum=0, extQtySum=0;

    // utility to test one row
    function test(row, dataField, filterVal, matchExact=false) {
      const v = (row.dataset[dataField]||'').toString().toLowerCase();
      return !filterVal
          || (!matchExact ? v.includes(filterVal) : v===filterVal);
    }

    // process Showcase
    document.querySelectorAll('.showcaseTable .group-header').forEach(h=>{
      const date = h.dataset.date;
      let groupVisible = false;
      document.querySelectorAll(`.showcaseTable .group-${date}`).forEach(r=>{
        const okDate = (!from || r.dataset.date>=from)
                    && (!to   || r.dataset.date<=to);
        const okRec  = test(r,'recipe', rf);
        const okCat  = test(r,'category', cf, true);
        const okDep  = test(r,'department', df, true);
        const show   = okDate && okRec && okCat && okDep;
        r.classList.toggle('d-none', !show);
        if(show) {
          groupVisible = true;
          // accumulate
          qtySum   += +r.dataset.qty   || 0;
          soldSum  += +r.dataset.sold  || 0;
          reuseSum += +r.dataset.reuse || 0;
          wasteSum += +r.dataset.waste || 0;
          showSum  += +r.dataset.revenue||0;
        }
      });
      h.classList.toggle('d-none', !groupVisible);
    });

    // process External
    document.querySelectorAll('.externalTable .group-header').forEach(h=>{
      const date = h.dataset.date;
      let groupVisible = false;
      document.querySelectorAll(`.externalTable .group-${date}`).forEach(r=>{
        const okDate = (!from || r.dataset.date>=from)
                    && (!to   || r.dataset.date<=to);
        const okRec  = test(r,'recipe', rf);
        const okCat  = test(r,'category', cf, true);
        const okDep  = test(r,'department', df, true);
        const show   = okDate && okRec && okCat && okDep;
        r.classList.toggle('d-none', !show);
        if(show) {
          groupVisible = true;
          retSum    += +r.dataset.returns||0;
          extQtySum += +r.dataset.qty    ||0;
          extSum    += +r.dataset.total  ||0;
        }
      });
      h.classList.toggle('d-none', !groupVisible);
    });

    // update summaries
    const grand = showSum + extSum;
    sumShowEl.textContent = showSum.toFixed(2);
    pctShowEl.textContent = grand? Math.round(showSum*100/grand)+'%' : '0%';
    sumExtEl .textContent = extSum.toFixed(2);
    pctExtEl .textContent = grand? Math.round(extSum*100/grand)+'%' : '0%';

    // update footers
    qtyShowEl.textContent   = qtySum;
    soldShowEl.textContent  = soldSum;
    reuseShowEl.textContent = reuseSum;
    wasteShowEl.textContent = wasteSum;
    footerShowEl.textContent= showSum.toFixed(2);

    retExtEl .textContent   = retSum;
    qtyExtEl .textContent   = extQtySum;
    footerExtEl.textContent = extSum.toFixed(2);
  }

  // wire up
  [fromIn,toIn,recIn,catIn,deptIn].forEach(el=>{
    el.addEventListener('input',applyFilter);
    el.addEventListener('change',applyFilter);
  });

  applyFilter();

  // group toggle
  document.querySelectorAll('.toggle-arrow').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const tr   = btn.closest('tr');
      const date = tr.dataset.date;
      const icon = btn.querySelector('i');
      document.querySelectorAll(`.group-${date}`).forEach(r=>{
        r.classList.toggle('d-none');
      });
      icon.classList.toggle('bi-caret-right-fill');
      icon.classList.toggle('bi-caret-down-fill');
    });
  });
});
</script>
@endsection



