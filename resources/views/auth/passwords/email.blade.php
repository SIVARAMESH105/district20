@extends('layouts.app_theme_login')

@section('content')
<section class="register-page">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-12">
                <div class="logo-login text-center">
                    <img src="{{ asset('images/logo-login-page.png') }}">
                </div>
            </div>
            <div class="col-md-6">
                <form method="POST" class="register" action="{{ route('password.email') }}">
                 @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">Forgot Password</p>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif                            
                        </div>                        
                        <div class="col-md-12">
                            <div class="form-group ">                                
                                <div class="form-group-field">
                                    <input id="email" placeholder="E-Mail Address" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="submit" type="submit">{{ __('Send Password Reset Link') }}</button>
                            @if(!Auth::user())
                                <p><a href="{{ route('login') }}">Login</a></p>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection