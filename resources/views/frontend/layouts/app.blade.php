<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap"
        rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoWSClFY5OSZrN+8POMcF2Q3oV3gy1p25jmXoDkFdEY5b3+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" /> --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        * {
            font-family: "Libre Baskerville", "montserrat" !important;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <title>@yield('title', 'Admin Dashboard')</title>


    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
   
    <link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.atom-one-dark.min.css') }}">
  
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />

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

        .blink {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0% {
                background-color: transparent;
            }

            50% {
                background-color: yellow;
            }

            100% {
                background-color: transparent;
            }
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


    <style>
        .dropdown a::after{color: #e2ae76}
.sidebar-menu-area{
    overflow: auto;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none;  /* IE 10+ */
    border:none !important; 
}

.sidebar-menu-area::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}        .sidebar {
            /* position: relative; */
            background: url('{{ asset("assets/images/asset/sidebar.jpg") }}')  center/cover no-repeat;
            color: #fff;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .sidebar::before {
            content: "";
            position: absolute;
            inset: 0;
            background-color: rgba(0, 24, 72, 0.763);
            pointer-events: none;
            z-index: 0;
        }

        .sidebar>* {
            position: relative;
            z-index: 1;
            /* lift logo, menu, button above overlay */
        }

        /* ─── Logout Button ──────────────────────────────────────────────────── */
        .sidebar-logout {
            margin-top: auto;
            /* push to bottom of flex container */
            
        }

        .sidebar-logo {
            border-right: 0 solid white;
                border-bottom: none;

        }

        .logout-btn {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 1rem;
            background-color: #ff4d4d;
            color: #041930;
            background-color: #e2ae76;
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .logout-btn:hover {
            background-color: #041930;
            color: #e2ae76 !important;

        }

        .logout-icon {
            margin-right: 0.5rem;
            font-size: 1.25rem;
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
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
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
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Disable up and down arrow key changes on all number inputs
                document.querySelectorAll('input[type="number"]').forEach(input => {
                    input.addEventListener('keydown', function(e) {
                        if (e.key === "ArrowUp" || e.key === "ArrowDown") {
                            e.preventDefault(); // Prevents the default behavior
                        }
                    });
                });
            });
        </script>


        <!-- Pusher JS -->
    

        {{-- <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.Pusher.logToConsole = false;
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: '{{ config('broadcasting.connections.pusher.key') }}',
                    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                    forceTLS: true
                });

                window.Echo.channel('news-notifications')
                    .listen('NewsNotificationCreated', e => {
                        const html = `
          <a href="javascript:void(0)"
             class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
            <div class="d-flex align-items-center gap-3">
              <span class="w-44-px h-44-px bg-success-subtle rounded-circle d-flex justify-content-center align-items-center">
                <i class="bi bi-bell text-xxl"></i>
              </span>
              <div>
                <h6 class="text-md fw-semibold mb-1">${e.title}</h6>
                <p class="mb-0 text-sm text-secondary-light">${e.content}</p>
              </div>
            </div>
            <span class="text-sm text-secondary-light">Just now</span>
          </a>`;

                        document.getElementById('notifList')
                            .insertAdjacentHTML('afterbegin', html);

                        ['notifBadge', 'notifCountHeader'].forEach(id => {
                            const el = document.getElementById(id);
                            if (!el) return;
                            el.textContent = (parseInt(el.textContent) || 0) + 1;
                        });

                        document.getElementById('notifToggle')
                            .classList.add('blink');
                    });
            });
        </script> --}}


        {{-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script> --}}


        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>



        <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
        <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/lib/prism.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/lib/file-upload.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script> --}}
        <script src="{{ asset('assets/js/app.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/homeOneChart.js') }}"></script> --}}
</body>

</html>
