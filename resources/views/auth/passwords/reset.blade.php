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
                <form method="POST" class="register" action="{{ route('password.update') }}">
                 @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">Reset Password</p>                                                       
                        </div>                        
                        <div class="col-md-12">
                            <div class="form-group ">                                
                                <div class="form-group-field">
                                    <input id="email" type="email"  placeholder="E-Mail Address" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}"  autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group ">                                
                                <div class="form-group-field">
                                    <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group ">                                
                                <div class="form-group-field">
                                    <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="submit" type="submit">{{ __('Reset Password') }}</button>
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