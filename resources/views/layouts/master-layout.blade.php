<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ @$title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-[#f8f8f8] text-[#1b1b18] flex min-h-screen flex-col light">
    <!-- toast -->
    @if (\Session::has('success'))
    <x-toast :message="Session::get('success')" />
    @elseif(Session::has('error'))
    <x-toast type="danger" :message="Session::get('error')" />
    @endif

    {{-- error message --}}
    @if (\Session::has('errorMessage'))
    <div class="alert alert-danger">
        {{ Session::get('errorMessage') }}
    </div>
    @endif


    {{-- navbar --}}
    @auth
    @include('partial.navbar')
    @endauth
    {{-- end navbar --}}

    <div class="container mx-auto px-4">
        @yield('content')
    </div>

    @yield('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
