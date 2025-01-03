@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.Login')
@endsection
@section('content')
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <a href="{{ url('index') }}" class="mb-5 d-block auth-logo">
                            @if (config('app.locale') == 'ar')
                                <img src="{{ URL::asset('/assets/images/logo-dark-ar.png') }}" alt="" height="80"
                                    class="logo logo-dark">
                                <img src="{{ URL::asset('/assets/images/logo-light-ar.png') }}" alt=""
                                    height="200" class="logo logo-light">
                            @else
                                <img src="{{ URL::asset('/assets/images/logo-dark.png') }}" alt="" height="80"
                                    class="logo logo-dark">
                                <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="200"
                                    class="logo logo-light">
                            @endif
                        </a>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                @if (session('status'))
                                    <div class="alert alert-info" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif
                            </div>
                            <div class="text-center mt-2">
                                <h5 class="text-primary">@lang('auth.welcomeBack')</h5>
                                <p class="text-muted">@lang('auth.signinMessage')</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="email">@lang('auth.email')</label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" id="email"
                                            placeholder="@lang('auth.enterEmail')">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <div class="float-end">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}"
                                                    class="text-muted">@lang('auth.forgotPassword')</a>
                                            @endif
                                        </div>
                                        <label class="form-label" for="userpassword">@lang('auth.password')</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                             name="password" id="userpassword" placeholder="@lang('auth.enterPassword')">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="auth-remember-check"
                                            name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auth-remember-check">@lang('auth.rememberme')</label>
                                    </div>

                                    <div class="mt-3 text-end">
                                        <button class="btn btn-primary w-sm waves-effect waves-light"
                                            type="submit">@lang('auth.login')</button>
                                    </div>



                                    {{-- <div class="mt-4 text-center">
                                        <p class="mb-0">@lang('auth.dontHaveAccount') <a href="{{ url('register') }}"
                                                class="fw-medium text-primary"> @lang('auth.signUpNow') </a> </p>
                                    </div> --}}
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
@endsection
