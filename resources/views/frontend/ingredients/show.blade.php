@extends('frontend.layouts.app')

@section('title', 'View Ingredient')

@section('content')
<div class="container py-5">
  <div class="card border-primary shadow-lg rounded-3 overflow-hidden">
    <!-- Header with icon and title -->
    <div class="card-header d-flex align-items-center gap-2" style="background-color: #041930; color: #e2ae76;">
      <!-- SVG Icon -->
      <div style="width: 30px; height: 30px;">
          <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="#e2ae76" width="100%" height="100%">
              <path d="M479.605,91.769c-23.376,23.376-66.058,33.092-79.268,19.882c-13.21-13.21-3.494-55.892,19.883-79.268s85.999-26.614,85.999-26.614S502.982,68.393,479.605,91.769z"/>
              <path d="M506.218,5.785L400.345,111.658c13.218,13.2,55.888,3.483,79.26-19.889C502.864,68.511,506.186,6.411,506.218,5.785z" fill="#FFAE33"/>
              <path d="M432.367,200.156c-33.059,0-70.11-23.311-70.11-41.992s37.052-41.992,70.11-41.992s79.629,41.992,79.629,41.992S465.426,200.156,432.367,200.156z" fill="#FFAE33"/>
              <path d="M311.84,79.629c0,33.059,23.311,70.11,41.992,70.11s41.992-37.052,41.992-70.11S353.832,0,353.832,0S311.84,46.571,311.84,79.629z"/>
              <path d="M367.516,265.006c-33.059,0-70.11-23.311-70.11-41.992s37.052-41.992,70.11-41.992s79.629,41.992,79.629,41.992S400.575,265.006,367.516,265.006z" fill="#FFAE33"/>
              <path d="M246.99,144.48c0,33.059,23.311,70.11,41.992,70.11c18.681,0,41.992-37.052,41.992-70.11S288.982,64.85,288.982,64.85S246.99,111.421,246.99,144.48z"/>
              <path d="M302.666,329.857c-33.059,0-70.11-23.311-70.11-41.992c0-18.681,37.052-41.992,70.11-41.992s79.629,41.992,79.629,41.992S335.726,329.857,302.666,329.857z" fill="#FFAE33"/>
              <path d="M182.14,209.33c0,33.059,23.311,70.11,41.992,70.11s41.992-37.052,41.992-70.11s-41.992-79.629-41.992-79.629S182.14,176.27,182.14,209.33z"/>
              <path d="M237.025,395.498c-33.059,0-70.11-23.311-70.11-41.992c0-18.681,37.052-41.992,70.11-41.992s79.629,41.992,79.629,41.992S270.085,395.498,237.025,395.498z" fill="#FFAE33"/>
              <path d="M116.498,274.97c0,33.059,23.31,70.11,41.992,70.11s41.992-37.052,41.992-70.11s-41.992-79.629-41.992-79.629S116.498,241.912,116.498,274.97z"/>
              <path d="M170.438,462.084c-33.059,0-70.11-23.311-70.11-41.992c0-18.681,37.052-41.992,70.11-41.992s79.629,41.992,79.629,41.992S203.497,462.084,170.438,462.084z" fill="#FFAE33"/>
              <path d="M49.912,341.558c0,33.059,23.31,70.11,41.992,70.11s41.992-37.052,41.992-70.11s-41.992-79.629-41.992-79.629S49.912,308.499,49.912,341.558z"/>
              <path d="M4.917,507.087c-6.552-6.552-6.552-17.174,0-23.725L404.75,83.527c6.552-6.552,17.174-6.552,23.725,0c6.552,6.552,6.552,17.174,0,23.725L28.643,507.087C22.091,513.637,11.468,513.637,4.917,507.087z" fill="#F29C2A"/>
          </svg>
      </div>
  
      <!-- Title -->
      <h4 class="mb-0" style="color: #e2ae76;">{{ $ingredient->ingredient_name }}</h4>
  </div>
  

    <div class="card-body" >
      <!-- Details in a 2-column grid -->
      <div class="row g-4 mb-3" style="width: 50%">
        <div class="col-md-6">
         <strong><p class="text-uppercase text-muted small fw-bold" style="font-size: 25px;">Price per kg</p></strong> 
          <p class="fw-semibold" style="font-size: 25px;">€{{ number_format($ingredient->price_per_kg, 2) }}</p>
        </div>
      
        <div class="col-md-6">
          <strong><p class="text-uppercase text-muted small fw-bold" style="font-size: 25px;">Last Updated</p></strong> 
          <p style="font-size: 25px;">{{ $ingredient->updated_at->format('Y-m-d H:i') }}</p>
        </div>
      </div>
      

      <hr class="border-secondary">

      <!-- Action buttons -->
      <div class="d-flex justify-content-end gap-2">
        <!-- Edit Button - Gold -->
        <a href="{{ route('ingredients.edit', $ingredient) }}" 
           class="btn btn-lg" 
           style="border: 1px solid #e2ae76; color: #e2ae76;">
            <iconify-icon icon="lucide:edit" class="me-1" style="--iconify-icon-color: #e2ae76;"></iconify-icon>
            Edit
        </a>
    
        <!-- View Button - Blue -->
        <a href="{{ route('ingredients.index') }}" 
           class="btn btn-lg" 
           style="border: 1px solid #041930; color: #041930;">
            <iconify-icon icon="lucide:arrow-left" class="me-1" style="--iconify-icon-color: #041930;"></iconify-icon>
            Back to List
        </a>
    
        <!-- Delete Button - Red -->
        <form action="{{ route('ingredients.destroy', $ingredient) }}"
              method="POST"
              onsubmit="return confirm('Delete this ingredient?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-lg" 
                    style="background-color: #ff4c4c; color: white; border: 1px solid #ff4c4c;">
                <iconify-icon icon="mingcute:delete-2-line" class="me-1" style="--iconify-icon-color: white;"></iconify-icon>
                Delete
            </button>
        </form>
    </div>
    
      
    </div>
  </div>
</div>
@endsection
