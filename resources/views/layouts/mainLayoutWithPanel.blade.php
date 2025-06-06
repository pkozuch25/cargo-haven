<!DOCTYPE html>

<html data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}" class="dark">


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
  {{-- @vite(['resources/css/app.css']) --}}
  <link href="{{ mix('css/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <link href="{{ mix('css/flag-icons.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
  <link rel="shortcut icon" href="{{ mix('png/project-logo.png') }}" type="image/png">
  {{-- Include core + vendor Styles --}}
  @livewireStyles()
</head>
<!-- END: Head-->

<body class="font-sans antialiased dark:bg-gray-900" style="color: rgb(243 244 246);">
    <div class="min-vh-100">
        @include('layouts.navigation')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-10 space-y-6">
                <div class="p-4 sm:p-8 dark:bg-gray-800 shadow sm:rounded-lg">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
    @stack('javascript')
    @livewireScripts
</body>

@include('layouts.layoutScripts')
