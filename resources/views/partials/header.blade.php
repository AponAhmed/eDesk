<!-- Mobile menu button -->
<div class="flex items-center">
    <button id="menuTaggler" type="button"
        class="relative inline-flex items-center justify-center rounded-sm bg-gray-50 p-1 text-gray-400 hover:bg-gray-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
        aria-controls="mobile-menu" aria-expanded="false">
        <span class="absolute -inset-0.5"></span>
        <span class="sr-only">Open main menu</span>
        <!-- Menu open: "hidden", Menu closed: "block" -->
        <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
        <!-- Menu open: "block", Menu closed: "hidden" -->
        <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div class="bg-slate-700 p-1 h-8 w-8 rounded-md md:hidden flex items-center justify-center ml-3">
        <svg class="w-5 h-5 pl-[1px] pt-[1px]" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            @if (request()->segment(1) == 'edesk')
                <path
                    d="M11.7441 0.431824V4.27003C9.16381 4.67499 4.11175 6.96299 3.69129 14.5747H17.7778V18.5692H10.8165H3.85524C5.00591 25.2462 11.0466 27.6505 14.2222 28.1294V21.0523H17.7778V31.908C12.9293 32.6637 0 28.8851 0 16.0378C0 5.75988 7.82941 1.35136 11.7441 0.431824Z"
                    fill="#22D3EE"></path>
                <path
                    d="M14.2222 12.567H17.8855V3.93011C22.6263 3.93011 28.5615 9.43611 28.4444 16.2376C28.3274 23.0392 23.0572 26.6019 20.3636 27.6815V31.5681C29.8451 28.3292 32 20.9879 32 16.2376C32 2.74255 19.9327 -0.42431 14.2222 0.0435196V12.567Z"
                    fill="white"></path>
            @else
                <path
                    d="M11.7441 0.643402V4.45588C9.05051 4.87579 3.6633 7.80143 3.6633 16.1447C3.6633 24.488 10.7026 27.6282 14.2222 28.1553V18.8257H10.6667V15.0724H17.7778V31.9086C12.9293 32.6593 0 28.906 0 16.1447C0 5.93575 7.82941 1.55677 11.7441 0.643402Z"
                    fill="#F03A3A"></path>
                <path
                    d="M14.2222 12.4827H17.8855V3.90376C22.6263 3.90376 28.5615 9.37285 28.4445 16.1288C28.3274 22.8847 23.0572 26.4236 20.3636 27.4959V31.3565C29.8451 28.1394 32 20.8472 32 16.1288C32 2.72415 19.9327 -0.421482 14.2222 0.0432127V12.4827Z"
                    fill="white"></path>
            @endif
        </svg>
    </div>

    @if (request()->segment(1) == 'gdesk')
        <a href="{{ route('gdesk.newmessage') }}"
            class="popup  bg-[#f03a3a] px-2 rounded-md flex items-center ml-3 text-white h-7 text-sm" data-w="900">
            <svg class="w-4 h-4 text-white stroke-white mr-1" viewBox="0 0 512 512">
                <path d="M384 224v184a40 40 0 01-40 40H104a40 40 0 01-40-40V168a40 40 0 0140-40h167.48" fill="none"
                    stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                <path class="fill-white"
                    d="M459.94 53.25a16.06 16.06 0 00-23.22-.56L424.35 65a8 8 0 000 11.31l11.34 11.32a8 8 0 0011.34 0l12.06-12c6.1-6.09 6.67-16.01.85-22.38zM399.34 90L218.82 270.2a9 9 0 00-2.31 3.93L208.16 299a3.91 3.91 0 004.86 4.86l24.85-8.35a9 9 0 003.93-2.31L422 112.66a9 9 0 000-12.66l-9.95-10a9 9 0 00-12.71 0z" />
            </svg>Write new</a>
    @endif
</div>


<div class="dropdown relative inline-block text-left">
    <div>
        <button type="button"
            class=" dropdown-toggler inline-flex  w-full justify-center gap-x-1.5 rounded-md px-3 py-1 text-sm font-semibold text-slate-600"
            id="menu-button" aria-expanded="true" aria-haspopup="true">
            {{ auth()->user()->name }}
            <svg class="-mr-1 h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    <div class="dropdown-content absolute right-0 top-full z-10 mb-2 w-56  rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
        <div class="py-1" role="none">
            <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
            <a href="{{ route('home') }}"
                class="edeskmenu relative text-gray-700 flex items-center px-4 py-2 text-sm hover:bg-slate-100"
                role="menuitem" tabindex="-1" id="menu-item-0">
                <div class="bg-slate-700 p-1 rounded-md mr-2">
                    <svg class="w-4 h-4 p-[2px]" width="32" height="32" viewBox="0 0 32 32" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.7441 0.431824V4.27003C9.16381 4.67499 4.11175 6.96299 3.69129 14.5747H17.7778V18.5692H10.8165H3.85524C5.00591 25.2462 11.0466 27.6505 14.2222 28.1294V21.0523H17.7778V31.908C12.9293 32.6637 0 28.8851 0 16.0378C0 5.75988 7.82941 1.35136 11.7441 0.431824Z"
                            fill="#22D3EE"></path>
                        <path
                            d="M14.2222 12.567H17.8855V3.93011C22.6263 3.93011 28.5615 9.43611 28.4444 16.2376C28.3274 23.0392 23.0572 26.6019 20.3636 27.6815V31.5681C29.8451 28.3292 32 20.9879 32 16.2376C32 2.74255 19.9327 -0.42431 14.2222 0.0435196V12.567Z"
                            fill="white"></path>
                    </svg>
                </div>
                <span>eDesk</span>
            </a>
            <a href="{{ route('gdesk.index') }}"
                class="gdeskmenu text-gray-700 flex items-center px-4 py-2 text-sm hover:bg-slate-100 relative"
                role="menuitem" tabindex="-1" id="menu-item-0">
                <div class="bg-slate-700 p-1 rounded-md mr-2">
                    <svg class="w-4 h-4 p-[2px]" width="32" height="32" viewBox="0 0 32 32" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.7441 0.643402V4.45588C9.05051 4.87579 3.6633 7.80143 3.6633 16.1447C3.6633 24.488 10.7026 27.6282 14.2222 28.1553V18.8257H10.6667V15.0724H17.7778V31.9086C12.9293 32.6593 0 28.906 0 16.1447C0 5.93575 7.82941 1.55677 11.7441 0.643402Z"
                            fill="#F03A3A"></path>
                        <path
                            d="M14.2222 12.4827H17.8855V3.90376C22.6263 3.90376 28.5615 9.37285 28.4445 16.1288C28.3274 22.8847 23.0572 26.4236 20.3636 27.4959V31.3565C29.8451 28.1394 32 20.8472 32 16.1288C32 2.72415 19.9327 -0.421482 14.2222 0.0432127V12.4827Z"
                            fill="white"></path>
                    </svg>
                </div>
                <span>gDesk</span>
            </a>
            <a href="{{ route('general') }}"
                class="flex  items-center text-gray-700 px-4 py-2 text-sm hover:bg-slate-100" role="menuitem"
                tabindex="-1" id="menu-item-0">
                <div class="bg-slate-100 p-1 rounded-md mr-2">
                    <svg class="h-4 w-4 text-slate-700 fill-slate-700" viewBox="0 0 512 512">
                        <path
                            d="M456.7 242.27l-26.08-4.2a8 8 0 01-6.6-6.82c-.5-3.2-1-6.41-1.7-9.51a8.08 8.08 0 013.9-8.62l23.09-12.82a8.05 8.05 0 003.9-9.92l-4-11a7.94 7.94 0 00-9.4-5l-25.89 5a8 8 0 01-8.59-4.11q-2.25-4.2-4.8-8.41a8.16 8.16 0 01.7-9.52l17.29-19.94a8 8 0 00.3-10.62l-7.49-9a7.88 7.88 0 00-10.5-1.51l-22.69 13.63a8 8 0 01-9.39-.9c-2.4-2.11-4.9-4.21-7.4-6.22a8 8 0 01-2.5-9.11l9.4-24.75A8 8 0 00365 78.77l-10.2-5.91a8 8 0 00-10.39 2.21l-16.64 20.84a7.15 7.15 0 01-8.5 2.5s-5.6-2.3-9.8-3.71A8 8 0 01304 87l.4-26.45a8.07 8.07 0 00-6.6-8.42l-11.59-2a8.07 8.07 0 00-9.1 5.61l-8.6 25.05a8 8 0 01-7.79 5.41h-9.8a8.07 8.07 0 01-7.79-5.41l-8.6-25.05a8.07 8.07 0 00-9.1-5.61l-11.59 2a8.07 8.07 0 00-6.6 8.42l.4 26.45a8 8 0 01-5.49 7.71c-2.3.9-7.3 2.81-9.7 3.71-2.8 1-6.1.2-8.8-2.91l-16.51-20.34A8 8 0 00156.75 73l-10.2 5.91a7.94 7.94 0 00-3.3 10.09l9.4 24.75a8.06 8.06 0 01-2.5 9.11c-2.5 2-5 4.11-7.4 6.22a8 8 0 01-9.39.9L111 116.14a8 8 0 00-10.5 1.51l-7.49 9a8 8 0 00.3 10.62l17.29 19.94a8 8 0 01.7 9.52q-2.55 4-4.8 8.41a8.11 8.11 0 01-8.59 4.11l-25.89-5a8 8 0 00-9.4 5l-4 11a8.05 8.05 0 003.9 9.92L85.58 213a7.94 7.94 0 013.9 8.62c-.6 3.2-1.2 6.31-1.7 9.51a8.08 8.08 0 01-6.6 6.82l-26.08 4.2a8.09 8.09 0 00-7.1 7.92v11.72a7.86 7.86 0 007.1 7.92l26.08 4.2a8 8 0 016.6 6.82c.5 3.2 1 6.41 1.7 9.51a8.08 8.08 0 01-3.9 8.62L62.49 311.7a8.05 8.05 0 00-3.9 9.92l4 11a7.94 7.94 0 009.4 5l25.89-5a8 8 0 018.59 4.11q2.25 4.2 4.8 8.41a8.16 8.16 0 01-.7 9.52l-17.29 19.96a8 8 0 00-.3 10.62l7.49 9a7.88 7.88 0 0010.5 1.51l22.69-13.63a8 8 0 019.39.9c2.4 2.11 4.9 4.21 7.4 6.22a8 8 0 012.5 9.11l-9.4 24.75a8 8 0 003.3 10.12l10.2 5.91a8 8 0 0010.39-2.21l16.79-20.64c2.1-2.6 5.5-3.7 8.2-2.6 3.4 1.4 5.7 2.2 9.9 3.61a8 8 0 015.49 7.71l-.4 26.45a8.07 8.07 0 006.6 8.42l11.59 2a8.07 8.07 0 009.1-5.61l8.6-25a8 8 0 017.79-5.41h9.8a8.07 8.07 0 017.79 5.41l8.6 25a8.07 8.07 0 009.1 5.61l11.59-2a8.07 8.07 0 006.6-8.42l-.4-26.45a8 8 0 015.49-7.71c4.2-1.41 7-2.51 9.6-3.51s5.8-1 8.3 2.1l17 20.94A8 8 0 00355 439l10.2-5.91a7.93 7.93 0 003.3-10.12l-9.4-24.75a8.08 8.08 0 012.5-9.12c2.5-2 5-4.1 7.4-6.21a8 8 0 019.39-.9L401 395.66a8 8 0 0010.5-1.51l7.49-9a8 8 0 00-.3-10.62l-17.29-19.94a8 8 0 01-.7-9.52q2.55-4.05 4.8-8.41a8.11 8.11 0 018.59-4.11l25.89 5a8 8 0 009.4-5l4-11a8.05 8.05 0 00-3.9-9.92l-23.09-12.82a7.94 7.94 0 01-3.9-8.62c.6-3.2 1.2-6.31 1.7-9.51a8.08 8.08 0 016.6-6.82l26.08-4.2a8.09 8.09 0 007.1-7.92V250a8.25 8.25 0 00-7.27-7.73zM256 112a143.82 143.82 0 01139.38 108.12A16 16 0 01379.85 240H274.61a16 16 0 01-13.91-8.09l-52.1-91.71a16 16 0 019.85-23.39A146.94 146.94 0 01256 112zM112 256a144 144 0 0143.65-103.41 16 16 0 0125.17 3.47L233.06 248a16 16 0 010 15.87l-52.67 91.7a16 16 0 01-25.18 3.36A143.94 143.94 0 01112 256zm144 144a146.9 146.9 0 01-38.19-4.95 16 16 0 01-9.76-23.44l52.58-91.55a16 16 0 0113.88-8H379.9a16 16 0 0115.52 19.88A143.84 143.84 0 01256 400z">
                        </path>
                    </svg>
                </div>
                <span>Settings</span>
            </a>

            <a class=" flex items-center  text-gray-700 w-full px-4 py-2 text-left text-sm  hover:bg-slate-100"
                href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                <div class="bg-slate-100 p-1 rounded-md mr-2">
                    <svg class="h-4 w-4 text-slate-700 fill-slate-700" viewBox="0 0 512 512">
                        <path
                            d="M304 336v40a40 40 0 01-40 40H104a40 40 0 01-40-40V136a40 40 0 0140-40h152c22.09 0 48 17.91 48 40v40M368 336l80-80-80-80M176 256h256"
                            fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="32" />
                    </svg>
                </div>
                {{ __('Logout') }}

            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>
