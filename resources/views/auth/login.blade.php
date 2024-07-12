<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login : eDesk</title>
    @vite(['resources/sass/login.scss'])
</head>

<body>
    <div class="hdesk-wrap">
        <div class="main-wrap">
            <div class="login-card">
                <div class="login-card-left">
                    <div class="card-header"><svg id="logoSvg" width="150" height="52" viewBox="0 0 67 32"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.7441 0.431824V4.27003C9.16381 4.67499 4.11175 6.96299 3.69129 14.5747H17.7778V18.5692H10.8165H3.85524C5.00591 25.2462 11.0466 27.6505 14.2222 28.1294V21.0523H17.7778V31.908C12.9293 32.6637 0 28.8851 0 16.0378C0 5.75988 7.82941 1.35136 11.7441 0.431824Z"
                                fill="#22D3EE"></path>
                            <path
                                d="M14.2222 12.567H17.8855V3.93011C22.6263 3.93011 28.5615 9.43611 28.4445 16.2376C28.3274 23.0392 23.0572 26.6019 20.3636 27.6815V31.5681C29.8451 28.3292 32 20.9879 32 16.2376C32 2.74255 19.9327 -0.42431 14.2222 0.0435196V12.567Z"
                                fill="#555"></path>
                            <path class="hidden"
                                d="M57.2046 19.2102L57.1691 16.6179H57.5953L63.5612 10.5455H66.1535L59.797 16.973H59.6194L57.2046 19.2102ZM55.2515 24.1818V6H57.3467V24.1818H55.2515ZM63.9163 24.1818L58.5896 17.4347L60.0811 15.9787L66.5796 24.1818H63.9163Z"
                                fill="#555"></path>
                            <path class="hidden"
                                d="M53.8491 13.5994L51.967 14.1321C51.8486 13.8184 51.674 13.5136 51.4432 13.2177C51.2183 12.9158 50.9105 12.6673 50.5199 12.472C50.1293 12.2766 49.6291 12.179 49.0195 12.179C48.185 12.179 47.4896 12.3713 46.9332 12.756C46.3828 13.1348 46.1076 13.6172 46.1076 14.2031C46.1076 14.724 46.297 15.1353 46.6758 15.4372C47.0546 15.739 47.6464 15.9905 48.4513 16.1918L50.4755 16.6889C51.6947 16.9849 52.6032 17.4376 53.201 18.0472C53.7988 18.6509 54.0977 19.4292 54.0977 20.3821C54.0977 21.1634 53.8727 21.8617 53.4229 22.4773C52.979 23.0928 52.3576 23.5781 51.5586 23.9332C50.7596 24.2884 49.8304 24.4659 48.7709 24.4659C47.3801 24.4659 46.2289 24.1641 45.3175 23.5604C44.406 22.9567 43.8289 22.0748 43.5863 20.9148L45.5749 20.4176C45.7643 21.1515 46.1224 21.7019 46.6491 22.0689C47.1818 22.4358 47.8772 22.6193 48.7354 22.6193C49.712 22.6193 50.4873 22.4122 51.0614 21.9979C51.6414 21.5777 51.9315 21.0746 51.9315 20.4886C51.9315 20.0152 51.7657 19.6186 51.4343 19.299C51.1029 18.9735 50.5939 18.7308 49.9073 18.571L47.6346 18.0384C46.3858 17.7424 45.4684 17.2837 44.8825 16.6623C44.3024 16.0349 44.0124 15.2507 44.0124 14.3097C44.0124 13.5403 44.2285 12.8596 44.6605 12.2678C45.0985 11.6759 45.6933 11.2113 46.445 10.8739C47.2025 10.5366 48.0607 10.3679 49.0195 10.3679C50.369 10.3679 51.4284 10.6638 52.1978 11.2557C52.9731 11.8475 53.5236 12.6288 53.8491 13.5994Z"
                                fill="#555"></path>
                            <path class="hidden"
                                d="M37.3565 24.4659C36.0426 24.4659 34.9092 24.1759 33.9563 23.5959C33.0094 23.0099 32.2784 22.1932 31.7635 21.1456C31.2545 20.0921 31 18.867 31 17.4702C31 16.0734 31.2545 14.8423 31.7635 13.777C32.2784 12.7057 32.9946 11.8712 33.9119 11.2734C34.8352 10.6698 35.9124 10.3679 37.1435 10.3679C37.8537 10.3679 38.555 10.4863 39.2475 10.723C39.94 10.9598 40.5703 11.3445 41.1385 11.8771C41.7067 12.4039 42.1594 13.1023 42.4968 13.9723C42.8342 14.8423 43.0028 15.9136 43.0028 17.1861V18.0739H32.4915V16.2628H40.8722C40.8722 15.4934 40.7183 14.8068 40.4105 14.2031C40.1087 13.5994 39.6766 13.123 39.1143 12.7738C38.558 12.4246 37.901 12.25 37.1435 12.25C36.3089 12.25 35.5869 12.4572 34.9773 12.8715C34.3736 13.2798 33.909 13.8125 33.5835 14.4695C33.2579 15.1264 33.0952 15.8307 33.0952 16.5824V17.7898C33.0952 18.8196 33.2727 19.6926 33.6278 20.4087C33.9889 21.119 34.489 21.6605 35.1282 22.0334C35.7674 22.4003 36.5102 22.5838 37.3565 22.5838C37.907 22.5838 38.4041 22.5069 38.848 22.353C39.2978 22.1932 39.6855 21.9564 40.011 21.6428C40.3365 21.3232 40.5881 20.9266 40.7656 20.4531L42.7898 21.0213C42.5767 21.7079 42.2186 22.3116 41.7156 22.8324C41.2125 23.3473 40.591 23.7498 39.8512 24.0398C39.1114 24.3239 38.2798 24.4659 37.3565 24.4659Z"
                                fill="#555"></path>
                        </svg></div>
                </div>
                <div class="login-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form__group field">
                            <input type="email" class="form__field" placeholder="User Name" name="email"
                                id='email' value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <label for="email" class="form__label">User Name</label>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form__group field">
                            <input type="password" class="form__field" placeholder="Password" name="password"
                                id='password' required autocomplete="current-password">
                            <label for="password" class="form__label">Password</label>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group remember_wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label form-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>

                            {{-- @if (Route::has('password.request'))
                                <a class="btn-link  form-label" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif --}}
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
