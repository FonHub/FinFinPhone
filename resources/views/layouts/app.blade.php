<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Cashkub'))</title>
    <link rel="icon" type="image/png"
        href="{{ asset('assets/media/logo/logo.png') }}">

    {{-- โหลดทั้ง CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;500;600;700;800&family=Noto+Sans+Thai:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-wrapper {
            width: 100%;
        }

        .ts-control {
            min-height: 56px !important;
            border-radius: 16px !important;
            border: 1px solid #DCE6E0 !important;
            background: #FCFDFC !important;
            padding: 0 16px !important;
            font-size: 15px !important;
            color: #111827 !important;
            box-shadow: none !important;
            display: flex !important;
            align-items: center !important;
        }

        .ts-control input {
            font-size: 15px !important;
            color: #111827 !important;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #10A36A !important;
            box-shadow: 0 0 0 2px rgba(16, 163, 106, 0.2) !important;
        }

        .ts-dropdown {
            border-radius: 14px !important;
            border: 1px solid #DCE6E0 !important;
            overflow: hidden !important;
            font-size: 15px !important;
            z-index: 9999 !important;
        }

        .ts-dropdown .option {
            padding: 10px 14px !important;
        }

        .ts-dropdown .active {
            background: #EAF7F1 !important;
            color: #111827 !important;
        }

        .ts-wrapper.single .ts-control:after {
            border-color: #111827 transparent transparent transparent !important;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800">

    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
    @stack('scripts')
</body>

</html>
