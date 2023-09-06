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
                    <div class="card-header">{{ __('eDesk Login') }}</div>
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

                            @if (Route::has('password.request'))
                                <a class="btn-link  form-label" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
