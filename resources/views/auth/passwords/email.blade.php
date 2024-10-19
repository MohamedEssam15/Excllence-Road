@extends('layouts.master-without-nav')
@section('title')
{{__('auth.resetPassword')}}
@endsection
@section('content')
    <div class="account-pages my-5  pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div>
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
                        <div class="card">

                            <div class="card-body p-4">

                                <div class="text-center mt-2">
                                    <h5 class="text-primary">{{__('auth.resetPassword')}}</h5>
                                    <p class="text-muted">{{__('auth.resetPasswordText')}}</p>
                                </div>
                                <div class="p-2 mt-4">
                                    @if (session('status'))
                                        <div class="alert alert-success mb-4" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label" for="email">{{__('auth.email')}}</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}" id="email"
                                                placeholder="{{__('auth.enterEmail')}}">
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mt-3 text-end">
                                            <button class="btn btn-primary w-sm waves-effect waves-light"
                                                type="submit">{{__('auth.reset')}}</button>
                                        </div>


                                        <div class="mt-4 text-center">
                                            <p class="mb-0">{{__('auth.rememberIt')}} <a href="{{ url('login') }}"
                                                    class="fw-medium text-primary"> {{__('auth.login')}} </a></p>
                                        </div>
                                    </form>
                                </div>

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
