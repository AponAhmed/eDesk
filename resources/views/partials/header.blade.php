<!-- Mobile menu button -->
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

<div class="dropdown relative inline-block text-left">
    <div>
        <button type="button"
            class=" dropdown-toggler inline-flex  w-full justify-center gap-x-1.5 rounded-md px-3 py-1 text-sm font-semibold text-slate-600"
            id="menu-button" aria-expanded="true" aria-haspopup="true">
            Admin User
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
            <a href="#" class="text-gray-700 block px-4 py-2 text-sm hover:bg-slate-100" role="menuitem"
                tabindex="-1" id="menu-item-0">Account settings</a>

            <a class="text-gray-700 block w-full px-4 py-2 text-left text-sm  hover:bg-slate-100"
                href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>

