<!DOCTYPE html>

<html data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}">


<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
  <meta name="csrf-token" content="{{ csrf_token() }}">


  <title>@yield('title') - Cargo Haven</title>
  <script src="https://kit.fontawesome.com/4212350c3d.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/@tabler/icons@1.74.0/icons-react/dist/index.umd.min.js"></script>
  {{-- @vite(['resources/css/app.css']) --}}
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  {{-- Include core + vendor Styles --}}
  @livewireStyles()
</head>
<!-- END: Head-->

<body class="font-sans antialiased dark:bg-gray-900" style="color: rgb(243 244 246);">
    <div class="min-vh-100">
        @include('layouts.navigation')
        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @livewireScripts
</body>

@include('layouts.layoutScripts')

