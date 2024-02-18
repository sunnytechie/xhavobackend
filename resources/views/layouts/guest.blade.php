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

  <title>Authentication</title>
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/xhavo_favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
  <style>
    input[type="email"], input[type="password"] {
      border: 1px solid #ced4da;
      border-radius: 0 !important;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      color: #495057;
      background-color: #fff;
      background-clip: padding-box;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
  </style>
</head>

<body>

    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">

        <main>
            @yield('content')
        </main>

    </div>

    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  </body>

  </html>
