<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script>
        // Create a JavaScript object with the settings values
        const AiSettings = {
            ai: "{!! \App\Models\Settings::get('ai_provider', '') !!}",
            key: "{!! \App\Models\Settings::get('ai_api_key', '') !!}",
            model: "{!! \App\Models\Settings::get('ai_data_model', 'gemini-pro') !!}",
            about: `{!! str_replace(["\r\n", "\n", "\r"], "\\n", addslashes(\App\Models\Settings::get('ai_about_company', ''))) !!}`,
            temperature: "{!! \App\Models\Settings::get('ai_temperature', '0.7') !!}",
            signPrefix: "{!! \App\Models\Settings::get('ai_signeture_prefix', '') !!}",
        };
    </script>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <!-- Authentication Links -->
        @guest
            Not Authorized !
        @else
            <div class="flex h-screen">
                <div id="sidebar" class="bg-slate-900 h-screen w-10">
                    <div class="flex align-middle justify-items-center">
                        <a id="appLogo"
                            class="text-4xl font-light text-slate-300 m-auto w-5 overflow-hidden py-5  first-letter:text-cyan-400"
                            href="{{ url('/') }}">
                            @php
                                $currentParent = request()->segment(1) ?? 'edesk';
                                if ($currentParent == 'gdesk') {
                                    echo 'gDesk';
                                } else {
                                    echo config('app.name', 'Laravel');
                                }
                            @endphp
                        </a>
                    </div>
                    <div class="flex flex-col justify-between h-full nav-wraper pt-8">
                        {{-- Main Nav --}}
                        <div class="main-nav overflow-y-auto">
                            @include('partials.main-nav')
                        </div>
                        {{-- Sub Nav --}}
                        <div class="sub-nav">
                            @include('partials.sub-nav')
                        </div>
                    </div>
                </div>
                <div id="bodyWrap" class="w-full flex flex-col ox-h">
                    <div id="header" class="p-1 flex justify-between border-b border-solid border-gray-100">
                        @include('partials.header')
                    </div>
                    <main id="main" class="p-2 h-full ox-h flex  relative bg-gray-100">
                        {{-- details-open --}}
                        @if (Session::has('success'))
                            <div id="success-message"
                                class="absolute z-10 top-0 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"
                                role="alert">
                                <span class="block sm:inline">{{ Session::get('success') }}</span>
                            </div>
                        @endif

                        @if (Session::has('error'))
                            <div id="error-message"
                                class="absolute z-10 top-40 right-32 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"
                                role="alert">
                                <span class="block sm:inline">{{ Session::get('error') }}</span>
                            </div>
                        @endif
                        @yield('content')
                    </main>
                </div>
            </div>
        @endguest
    </div>
</body>

</html>
