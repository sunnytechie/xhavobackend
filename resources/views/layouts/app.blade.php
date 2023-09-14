
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="Place where customers meets all kinds of service providers." />
    <meta property="og:title" content="{{ config('app.name') }}" />
    <meta property="og:description" content="Place where customers meets all kinds of service providers." />
    <meta property="og:image" content="{{ asset('assets/images/logos/xhavo.png') }}" />
    <meta property="og:image:width" content="200" />
    <meta property="og:image:height" content="200" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:alt" content="{{ config('app.name') }}" />

    <meta name="description" content="Place where customers meets all kinds of service providers.">
    <meta name="author" content="Xhavo.app">
    <meta name="keywords" content="xhavo, app, professional works.">


  <title>Xhavo.app Administrator</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset("assets/images/logos/xhavo_favicon.png") }}" />
  <link rel="stylesheet" href="{{ asset("assets/css/styles.min.css") }}" />
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

    <!-- Sidebar Start -->
        @include('dashboard.snippets.app.sidebar')
    <!--  Sidebar End -->


    <!--  Main wrapper -->
    <div class="body-wrapper">


      <!--  Header Start -->
      @include('dashboard.snippets.app.header')
      <!--  Header End -->


      <div class="container-fluid">

        <!--  Content Start -->
            @yield('content')
        <!--  Content End -->

        @include('dashboard.snippets.app.footer')

      </div>


    </div>
  </div>


  <script src="{{ asset("assets/libs/jquery/dist/jquery.min.js") }}"></script>
  <script src="{{ asset("assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js") }}"></script>
  <script src="{{ asset("assets/js/sidebarmenu.js") }}"></script>
  <script src="{{ asset("assets/js/app.min.js") }}"></script>
  <script src="{{ asset("assets/libs/apexcharts/dist/apexcharts.min.js") }}"></script>
  <script src="{{ asset("assets/libs/simplebar/dist/simplebar.js") }}"></script>
  <script src="{{ asset("assets/js/dashboard.js") }}"></script>
</body>

</html>


{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html> --}}
