@php
    $menus = [
        [
            'name' => 'Inbox',
            'route' => 'messages.index',
            'routeParams' => ['status' => 'inbox'],
            'svg' => '<path d="M441.6 171.61L266.87 85.37a24.57 24.57 0 00-21.74 0L70.4 171.61A40 40 0 0048 207.39V392c0 22.09 18.14 40 40.52 40h335c22.38 0 40.52-17.91 40.52-40V207.39a40 40 0 00-22.44-35.78z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" /> <path d="M397.33 368L268.07 267.46a24 24 0 00-29.47 0L109.33 368M309.33 295l136-103M61.33 192l139 105" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />',
        ],
        [
            'name' => 'Redirect',
            'route' => 'messages.index',
            'routeParams' => ['status' => 'redirect'],
            'svg' => '<path d="M448 256L272 88v96C103.57 184 64 304.77 64 424c48.61-62.24 91.6-96 208-96v96z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"></path>',
        ],
        [
            'name' => 'Local',
            'route' => 'messages.index',
            'routeParams' => ['status' => 'local'],
            'svg' => '<path d="M256 48c-79.5 0-144 61.39-144 137 0 87 96 224.87 131.25 272.49a15.77 15.77 0 0025.5 0C304 409.89 400 272.07 400 185c0-75.61-64.5-137-144-137z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="256" cy="192" r="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>',
        ],
        [
            'name' => 'Replied',
            'route' => 'messages.index',
            'routeParams' => ['status' => 'sent'],
            'svg' => '<path d="M53.12 199.94l400-151.39a8 8 0 0110.33 10.33l-151.39 400a8 8 0 01-15-.34l-67.4-166.09a16 16 0 00-10.11-10.11L53.46 215a8 8 0 01-.34-15.06zM460 52L227 285" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>',
        ],
        [
            'name' => 'Spam',
            'route' => 'messages.index',
            'routeParams' => ['status' => 'spam'],
            'svg' => '<circle cx="256" cy="256" r="208" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32"></circle><path fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="32" d="M108.92 108.92l294.16 294.16"></path>',
        ],
        [
            'name' => 'Trash',
            'route' => 'messages.index',
            'routeParams' => ['status' => 'trash'],
            'svg' => '<path d="M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 112h352"/><path d="M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>',
        ],
        [
            'name' => 'Api Manager',
            'route' => 'domains.index',
            'svg' => '<circle cx="160" cy="96" r="48" fill="none" stroke="currentColor"  stroke-linecap="round" stroke-linejoin="round" stroke-width="32" /> <circle cx="160" cy="416" r="48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" /> <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"  d="M160 368V144" /><circle cx="352" cy="160" r="48" fill="none" stroke="currentColor"stroke-linecap="round" stroke-linejoin="round" stroke-width="32" /><path d="M352 208c0 128-192 48-192 160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />',
        ],
        [
            'name' => 'Settings',
            'route' => 'settings',
            'svg' =>
                '<path d="M456.7 242.27l-26.08-4.2a8 8 0 01-6.6-6.82c-.5-3.2-1-6.41-1.7-9.51a8.08 8.08 0 013.9-8.62l23.09-12.82a8.05 8.05 0 003.9-9.92l-4-11a7.94 7.94 0 00-9.4-5l-25.89 5a8 8 0 01-8.59-4.11q-2.25-4.2-4.8-8.41a8.16 8.16 0 01.7-9.52l17.29-19.94a8 8 0 00.3-10.62l-7.49-9a7.88 7.88 0 00-10.5-1.51l-22.69 13.63a8 8 0 01-9.39-.9c-2.4-2.11-4.9-4.21-7.4-6.22a8 8 0 01-2.5-9.11l9.4-24.75A8 8 0 00365 78.77l-10.2-5.91a8 8 0 00-10.39 2.21l-16.64 20.84a7.15 7.15 0 01-8.5 2.5s-5.6-2.3-9.8-3.71A8 8 0 01304 87l.4-26.45a8.07 8.07 0 00-6.6-8.42l-11.59-2a8.07 8.07 0 00-9.1 5.61l-8.6 25.05a8 8 0 01-7.79 5.41h-9.8a8.07 8.07 0 01-7.79-5.41l-8.6-25.05a8.07 8.07 0 00-9.1-5.61l-11.59 2a8.07 8.07 0 00-6.6 8.42l.4 26.45a8 8 0 01-5.49 7.71c-2.3.9-7.3 2.81-9.7 3.71-2.8 1-6.1.2-8.8-2.91l-16.51-20.34A8 8 0 00156.75 73l-10.2 5.91a7.94 7.94 0 00-3.3 10.09l9.4 24.75a8.06 8.06 0 01-2.5 9.11c-2.5 2-5 4.11-7.4 6.22a8 8 0 01-9.39.9L111 116.14a8 8 0 00-10.5 1.51l-7.49 9a8 8 0 00.3 10.62l17.29 19.94a8 8 0 01.7 9.52q-2.55 4-4.8 8.41a8.11 8.11 0 01-8.59 4.11l-25.89-5a8 8 0 00-9.4 5l-4 11a8.05 8.05 0 003.9 9.92L85.58 213a7.94 7.94 0 013.9 8.62c-.6 3.2-1.2 6.31-1.7 9.51a8.08 8.08 0 01-6.6 6.82l-26.08 4.2a8.09 8.09 0 00-7.1 7.92v11.72a7.86 7.86 0 007.1 7.92l26.08 4.2a8 8 0 016.6 6.82c.5 3.2 1 6.41 1.7 9.51a8.08 8.08 0 01-3.9 8.62L62.49 311.7a8.05 8.05 0 00-3.9 9.92l4 11a7.94 7.94 0 009.4 5l25.89-5a8 8 0 018.59 4.11q2.25 4.2 4.8 8.41a8.16 8.16 0 01-.7 9.52l-17.29 19.96a8 8 0 00-.3 10.62l7.49 9a7.88 7.88 0 0010.5 1.51l22.69-13.63a8 8 0 019.39.9c2.4 2.11 4.9 4.21 7.4 6.22a8 8 0 012.5 9.11l-9.4 24.75a8 8 0 003.3 10.12l10.2 5.91a8 8 0 0010.39-2.21l16.79-20.64c2.1-2.6 5.5-3.7 8.2-2.6 3.4 1.4 5.7 2.2 9.9 3.61a8 8 0 015.49 7.71l-.4 26.45a8.07 8.07 0 006.6 8.42l11.59 2a8.07 8.07 0 009.1-5.61l8.6-25a8 8 0 017.79-5.41h9.8a8.07 8.07 0 017.79 5.41l8.6 25a8.07 8.07 0 009.1 5.61l11.59-2a8.07 8.07 0 006.6-8.42l-.4-26.45a8 8 0 015.49-7.71c4.2-1.41 7-2.51 9.6-3.51s5.8-1 8.3 2.1l17 20.94A8 8 0 00355 439l10.2-5.91a7.93 7.93 0 003.3-10.12l-9.4-24.75a8.08 8.08 0 012.5-9.12c2.5-2 5-4.1 7.4-6.21a8 8 0 019.39-.9L401 395.66a8 8 0 0010.5-1.51l7.49-9a8 8 0 00-.3-10.62l-17.29-19.94a8 8 0 01-.7-9.52q2.55-4.05 4.8-8.41a8.11 8.11 0 018.59-4.11l25.89 5a8 8 0 009.4-5l4-11a8.05 8.05 0 00-3.9-9.92l-23.09-12.82a7.94 7.94 0 01-3.9-8.62c.6-3.2 1.2-6.31 1.7-9.51a8.08 8.08 0 016.6-6.82l26.08-4.2a8.09 8.09 0 007.1-7.92V250a8.25 8.25 0 00-7.27-7.73zM256 112a143.82 143.82 0 01139.38 108.12A16 16 0 01379.85 240H274.61a16 16 0 01-13.91-8.09l-52.1-91.71a16 16 0 019.85-23.39A146.94 146.94 0 01256 112zM112 256a144 144 0 0143.65-103.41 16 16 0 0125.17 3.47L233.06 248a16 16 0 010 15.87l-52.67 91.7a16 16 0 01-25.18 3.36A143.94 143.94 0 01112 256zm144 144a146.9 146.9 0 01-38.19-4.95 16 16 0 01-9.76-23.44l52.58-91.55a16 16 0 0113.88-8H379.9a16 16 0 0115.52 19.88A143.84 143.84 0 01256 400z" />',
        ],
    ];
    
    $currentRoute = \Illuminate\Support\Facades\Route::current();
    
@endphp

@foreach ($menus as $menu)
    @php
        $isActive = false;
        
        if (isset($menu['route']) && !empty($menu['route'])) {
            $routeName = $menu['route'];
        
            if ($currentRoute && $currentRoute->getName() === $routeName) {
                if (isset($menu['routeParams']) && is_array($menu['routeParams'])) {
                    $routeParams = $menu['routeParams'];
        
                    // Get the current route's parameters
                    $currentRouteParams = $currentRoute->parameters();
        
                    // Check if all route parameters match
                    $match = true;
                    foreach ($routeParams as $param => $value) {
                        if (!isset($currentRouteParams[$param]) || $currentRouteParams[$param] !== $value) {
                            $match = false;
                            break;
                        }
                    }
        
                    if ($match) {
                        $isActive = true;
                    }
                } else {
                    $isActive = true;
                }
            }
        }
        
    @endphp

    <div class="group relative flex gap-x-2 p-2 hover:bg-slate-700 @if ($isActive) bg-slate-700 @endif "
        title="{{ $menu['name'] }}">
        <div class="flex h-6 w-6 flex-none items-center justify-center menu-icon">
            <svg class="h-6 w-6 text-slate-300 fill-slate-300" viewBox="0 0 512 512">@php echo $menu['svg'] @endphp</svg>
        </div>
        <div class="flex items-center">
            <a href="@if (isset($menu['route']) && !empty($menu['route'])) {{ route($menu['route'], isset($menu['routeParams']) ? $menu['routeParams'] : []) }} @endif"
                class="font-normal text-sm text-slate-400 leading-none block">
                <span class="menu-name font-normal text-sm text-slate-400 leading-none hidden">{{ $menu['name'] }}</span>
                <span class="absolute inset-0"></span>
            </a>
        </div>
    </div>
@endforeach
