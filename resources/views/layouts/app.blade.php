<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ request()->segment(1) == 'gdesk' ? '/gd.svg' : '/ed.svg' }}" type="image/svg+xml">
    <title>{{ request()->segment(1) == 'gdesk' ? 'gDesk' : config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script>
        // Create a JavaScript object with the settings values
        const AiSettings = {
            ai: "{!! \App\Models\Settings::get('ai_provider', '') !!}",
            key: "{!! \App\Models\Settings::get('ai_api_key', '') !!}",
            model: "{!! \App\Models\Settings::get('ai_data_model', 'gemini-pro') !!}",
            about: `{!! str_replace(["\r\n", "\n", "\r"], "\\n", addslashes(\App\Http\Controllers\AiGenerate::getAboutInfo())) !!}`,
            temperature: "{!! \App\Models\Settings::get('ai_temperature', '0.7') !!}",
            signPrefix: "{!! str_replace(["\r\n", "\n", "\r"], "\\n", addslashes(\App\Models\Settings::get('ai_signeture_prefix', ''))) !!}"
        };
        const SUBAPP = "{{ request()->segment(1) ?? 'edesk' }}";
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
                            href="{{ request()->segment(1) == 'gdesk' ? url('/gdesk/messages') : url('/') }}">
                            @php
                                $currentParent = request()->segment(1) ?? 'edesk';
                                if ($currentParent == 'gdesk') {
                                    echo '
<svg id="logoSvg" width="40" height="32" viewBox="0 0 67 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.7441 0.643402V4.45588C9.05051 4.87579 3.6633 7.80143 3.6633 16.1447C3.6633 24.488 10.7026 27.6282 14.2222 28.1553V18.8257H10.6667V15.0724H17.7778V31.9086C12.9293 32.6593 0 28.906 0 16.1447C0 5.93575 7.82941 1.55677 11.7441 0.643402Z" fill="#F03A3A"/>
<path d="M14.2222 12.4827H17.8855V3.90376C22.6263 3.90376 28.5615 9.37285 28.4445 16.1288C28.3274 22.8847 23.0572 26.4236 20.3636 27.4959V31.3565C29.8451 28.1394 32 20.8472 32 16.1288C32 2.72415 19.9327 -0.421482 14.2222 0.0432127V12.4827Z" fill="white"/>
<path class="hidden" d="M57.2046 18.2102L57.1691 15.6179H57.5953L63.5612 9.54545H66.1535L59.797 15.973H59.6194L57.2046 18.2102ZM55.2515 23.1818V5H57.3467V23.1818H55.2515ZM63.9163 23.1818L58.5896 16.4347L60.0811 14.9787L66.5796 23.1818H63.9163Z" fill="white"/>
<path class="hidden" d="M53.8491 12.5994L51.967 13.1321C51.8486 12.8184 51.674 12.5136 51.4432 12.2177C51.2183 11.9158 50.9105 11.6673 50.5199 11.472C50.1293 11.2766 49.6291 11.179 49.0195 11.179C48.185 11.179 47.4896 11.3713 46.9332 11.756C46.3828 12.1348 46.1076 12.6172 46.1076 13.2031C46.1076 13.724 46.297 14.1353 46.6758 14.4372C47.0546 14.739 47.6464 14.9905 48.4513 15.1918L50.4755 15.6889C51.6947 15.9849 52.6032 16.4376 53.201 17.0472C53.7988 17.6509 54.0977 18.4292 54.0977 19.3821C54.0977 20.1634 53.8727 20.8617 53.4229 21.4773C52.979 22.0928 52.3576 22.5781 51.5586 22.9332C50.7596 23.2884 49.8304 23.4659 48.7709 23.4659C47.3801 23.4659 46.2289 23.1641 45.3175 22.5604C44.406 21.9567 43.8289 21.0748 43.5863 19.9148L45.5749 19.4176C45.7643 20.1515 46.1224 20.7019 46.6491 21.0689C47.1818 21.4358 47.8772 21.6193 48.7354 21.6193C49.712 21.6193 50.4873 21.4122 51.0614 20.9979C51.6414 20.5777 51.9315 20.0746 51.9315 19.4886C51.9315 19.0152 51.7657 18.6186 51.4343 18.299C51.1029 17.9735 50.5939 17.7308 49.9073 17.571L47.6346 17.0384C46.3858 16.7424 45.4684 16.2837 44.8825 15.6623C44.3024 15.0349 44.0124 14.2507 44.0124 13.3097C44.0124 12.5403 44.2285 11.8596 44.6605 11.2678C45.0985 10.6759 45.6933 10.2113 46.445 9.87394C47.2025 9.53658 48.0607 9.3679 49.0195 9.3679C50.369 9.3679 51.4284 9.66383 52.1978 10.2557C52.9731 10.8475 53.5236 11.6288 53.8491 12.5994Z" fill="white"/>
<path class="hidden" d="M37.3565 23.4659C36.0426 23.4659 34.9092 23.1759 33.9563 22.5959C33.0094 22.0099 32.2784 21.1932 31.7635 20.1456C31.2545 19.0921 31 17.867 31 16.4702C31 15.0734 31.2545 13.8423 31.7635 12.777C32.2784 11.7057 32.9946 10.8712 33.9119 10.2734C34.8352 9.66975 35.9124 9.3679 37.1435 9.3679C37.8537 9.3679 38.555 9.48628 39.2475 9.72302C39.94 9.95976 40.5703 10.3445 41.1385 10.8771C41.7067 11.4039 42.1594 12.1023 42.4968 12.9723C42.8342 13.8423 43.0028 14.9136 43.0028 16.1861V17.0739H32.4915V15.2628H40.8722C40.8722 14.4934 40.7183 13.8068 40.4105 13.2031C40.1087 12.5994 39.6766 12.123 39.1143 11.7738C38.558 11.4246 37.901 11.25 37.1435 11.25C36.3089 11.25 35.5869 11.4572 34.9773 11.8715C34.3736 12.2798 33.909 12.8125 33.5835 13.4695C33.2579 14.1264 33.0952 14.8307 33.0952 15.5824V16.7898C33.0952 17.8196 33.2727 18.6926 33.6278 19.4087C33.9889 20.119 34.489 20.6605 35.1282 21.0334C35.7674 21.4003 36.5102 21.5838 37.3565 21.5838C37.907 21.5838 38.4041 21.5069 38.848 21.353C39.2978 21.1932 39.6855 20.9564 40.011 20.6428C40.3365 20.3232 40.5881 19.9266 40.7656 19.4531L42.7898 20.0213C42.5767 20.7079 42.2186 21.3116 41.7156 21.8324C41.2125 22.3473 40.591 22.7498 39.8512 23.0398C39.1114 23.3239 38.2798 23.4659 37.3565 23.4659Z" fill="white"/>
</svg>
';
                                } else {
                                    echo '
<svg  id="logoSvg" width="40" height="32" viewBox="0 0 67 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M11.7441 0.431824V4.27003C9.16381 4.67499 4.11175 6.96299 3.69129 14.5747H17.7778V18.5692H10.8165H3.85524C5.00591 25.2462 11.0466 27.6505 14.2222 28.1294V21.0523H17.7778V31.908C12.9293 32.6637 0 28.8851 0 16.0378C0 5.75988 7.82941 1.35136 11.7441 0.431824Z" fill="#22D3EE"/>
<path d="M14.2222 12.567H17.8855V3.93011C22.6263 3.93011 28.5615 9.43611 28.4445 16.2376C28.3274 23.0392 23.0572 26.6019 20.3636 27.6815V31.5681C29.8451 28.3292 32 20.9879 32 16.2376C32 2.74255 19.9327 -0.42431 14.2222 0.0435196V12.567Z" fill="white"/>
<path class="hidden" d="M57.2046 19.2102L57.1691 16.6179H57.5953L63.5612 10.5455H66.1535L59.797 16.973H59.6194L57.2046 19.2102ZM55.2515 24.1818V6H57.3467V24.1818H55.2515ZM63.9163 24.1818L58.5896 17.4347L60.0811 15.9787L66.5796 24.1818H63.9163Z" fill="white"/>
<path class="hidden" d="M53.8491 13.5994L51.967 14.1321C51.8486 13.8184 51.674 13.5136 51.4432 13.2177C51.2183 12.9158 50.9105 12.6673 50.5199 12.472C50.1293 12.2766 49.6291 12.179 49.0195 12.179C48.185 12.179 47.4896 12.3713 46.9332 12.756C46.3828 13.1348 46.1076 13.6172 46.1076 14.2031C46.1076 14.724 46.297 15.1353 46.6758 15.4372C47.0546 15.739 47.6464 15.9905 48.4513 16.1918L50.4755 16.6889C51.6947 16.9849 52.6032 17.4376 53.201 18.0472C53.7988 18.6509 54.0977 19.4292 54.0977 20.3821C54.0977 21.1634 53.8727 21.8617 53.4229 22.4773C52.979 23.0928 52.3576 23.5781 51.5586 23.9332C50.7596 24.2884 49.8304 24.4659 48.7709 24.4659C47.3801 24.4659 46.2289 24.1641 45.3175 23.5604C44.406 22.9567 43.8289 22.0748 43.5863 20.9148L45.5749 20.4176C45.7643 21.1515 46.1224 21.7019 46.6491 22.0689C47.1818 22.4358 47.8772 22.6193 48.7354 22.6193C49.712 22.6193 50.4873 22.4122 51.0614 21.9979C51.6414 21.5777 51.9315 21.0746 51.9315 20.4886C51.9315 20.0152 51.7657 19.6186 51.4343 19.299C51.1029 18.9735 50.5939 18.7308 49.9073 18.571L47.6346 18.0384C46.3858 17.7424 45.4684 17.2837 44.8825 16.6623C44.3024 16.0349 44.0124 15.2507 44.0124 14.3097C44.0124 13.5403 44.2285 12.8596 44.6605 12.2678C45.0985 11.6759 45.6933 11.2113 46.445 10.8739C47.2025 10.5366 48.0607 10.3679 49.0195 10.3679C50.369 10.3679 51.4284 10.6638 52.1978 11.2557C52.9731 11.8475 53.5236 12.6288 53.8491 13.5994Z" fill="white"/>
<path class="hidden" d="M37.3565 24.4659C36.0426 24.4659 34.9092 24.1759 33.9563 23.5959C33.0094 23.0099 32.2784 22.1932 31.7635 21.1456C31.2545 20.0921 31 18.867 31 17.4702C31 16.0734 31.2545 14.8423 31.7635 13.777C32.2784 12.7057 32.9946 11.8712 33.9119 11.2734C34.8352 10.6698 35.9124 10.3679 37.1435 10.3679C37.8537 10.3679 38.555 10.4863 39.2475 10.723C39.94 10.9598 40.5703 11.3445 41.1385 11.8771C41.7067 12.4039 42.1594 13.1023 42.4968 13.9723C42.8342 14.8423 43.0028 15.9136 43.0028 17.1861V18.0739H32.4915V16.2628H40.8722C40.8722 15.4934 40.7183 14.8068 40.4105 14.2031C40.1087 13.5994 39.6766 13.123 39.1143 12.7738C38.558 12.4246 37.901 12.25 37.1435 12.25C36.3089 12.25 35.5869 12.4572 34.9773 12.8715C34.3736 13.2798 33.909 13.8125 33.5835 14.4695C33.2579 15.1264 33.0952 15.8307 33.0952 16.5824V17.7898C33.0952 18.8196 33.2727 19.6926 33.6278 20.4087C33.9889 21.119 34.489 21.6605 35.1282 22.0334C35.7674 22.4003 36.5102 22.5838 37.3565 22.5838C37.907 22.5838 38.4041 22.5069 38.848 22.353C39.2978 22.1932 39.6855 21.9564 40.011 21.6428C40.3365 21.3232 40.5881 20.9266 40.7656 20.4531L42.7898 21.0213C42.5767 21.7079 42.2186 22.3116 41.7156 22.8324C41.2125 23.3473 40.591 23.7498 39.8512 24.0398C39.1114 24.3239 38.2798 24.4659 37.3565 24.4659Z" fill="white"/>
</svg>
';
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
                <div id="bodyWrap" class="w-full flex flex-col ox-h ">
                    <div id="header"
                        class="dark:bg-gray-800 p-1 flex justify-between border-b border-solid border-gray-100 dark:border-gray-900">
                        @include('partials.header')
                    </div>
                    <main id="main" class="p-2 h-full ox-h flex  relative bg-gray-100 dark:bg-gray-800">
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
    @yield('script')
</body>

</html>
