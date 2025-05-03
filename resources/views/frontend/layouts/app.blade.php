<!doctype html>
<html lang="en" data-bs-theme="light">
<head>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ENjdO4Dr2bkBIFxQpeoWSClFY5OSZrN+8POMcF2Q3oV3gy1p25jmXoDkFdEY5b3+" crossorigin="anonymous">

<!-- Bootstrap Icons CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" integrity="sha384-zLKmEXIYF6DIrNJYOt+/EPOFQZCzIp1p7p7mu+h0vRSW+tVO4p5CzeZ+F0JB4lY+" crossorigin="anonymous">
<!-- Bootstrap Icons -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
/>
<meta name="csrf-token" content="{{ csrf_token() }}">


<!-- Select2 CSS -->
<link
  href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css"
  rel="stylesheet"
/>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<style>
  *{font-family: "Libre Baskerville", "montserrat" !important; }
</style>

{{-- Select2 CDN --}}
{{-- jQuery and Select2 --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link
  href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
  rel="stylesheet" />
  <title>@yield('title', 'Admin Dashboard')</title>


  <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16">  
  <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/editor-katex.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.atom-one-dark.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.quill.snow.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/flatpickr.min.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/full-calendar.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/jquery-jvectormap-2.0.5.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/magnific-popup.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/slick.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/prism.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/lib/file-upload.css') }}">

  <link rel="stylesheet" href="{{ asset('assets/css/lib/audioplayer.css') }}">  
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">


  <link
  rel="stylesheet"
  href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css"
/>

<style>
  /* 1) make the UL stretch and push last item down */
  .sidebar-menu-area {
      display: flex;
      flex-direction: column;
      height: 100%;
  }

  .sidebar-menu {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 0;
      margin: 0;
      list-style: none;
  }

  /* normal li’s keep their default spacing */
  .sidebar-menu li {
      margin: 0;
  }

  /* push .sidebar-academy all the way to the bottom */
  .sidebar-menu li.sidebar-academy {
      margin-top: auto;
      padding: 5vw 1vw;
      text-align: center;
  }

  /* style the link like a big button */
  .sidebar-menu li.sidebar-academy a {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .5rem;
      background-color: #f06292;
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      border-radius: .5rem;
      padding: .75rem 1.25rem;
      box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
      transition: background-color .2s, transform .1s;
  }

  .sidebar-menu li.sidebar-academy a:hover {
      background-color: #ec407a;
      transform: translateY(-2px);
  }

  /* optional: make the icon a bit larger */
  .sidebar-menu li.sidebar-academy .academy-icon {
      font-size: 1.25rem;
  }
</style>

  <style>
    .blink {
    animation: blink 1s infinite;
}

@keyframes blink {
    0% { background-color: transparent; }
    50% { background-color: yellow; }
    100% { background-color: transparent; }
}




  /* Remove number‐input spinners globally */

/* Chrome, Safari, Edge, Opera */
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

/* Newer browsers */
input[type=number] {
  appearance: none;
}

  </style>
</head>

<body>
  <!-- Start wrapper -->
  <main class="dashboard-main">
    @include('frontend.layouts.sidebar')
    @include('frontend.layouts.navbar')
    
    <!-- Main Content Wrapper -->
    
        <!-- Success Message -->
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @yield('content')
      </div>
    </div>
  <!-- End wrapper -->
  @yield('scripts')

  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>



  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script
    src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"
  ></script>
  <script
    src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"
  ></script>
  


  <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/prism.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>  
  <script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script>
  <script src="{{ asset('assets/js/app.js') }}"></script>
  <script src="{{ asset('assets/js/homeOneChart.js') }}"></script>
</body>
</html>