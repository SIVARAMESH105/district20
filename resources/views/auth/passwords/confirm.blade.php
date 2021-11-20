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
                <form method="POST" class="register" action="{{ route('password.confirm') }}">
                 @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-center">{{ __('Please confirm your password before continuing.') }}</p>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif                            
                        </div>                        
                        <div class="col-md-12">
                            <div class="form-group ">                         
                                <div class="form-group-field">
                                   <input id="password" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                </div>                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary submit">
                                {{ __('Confirm Password') }}
                            </button>
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection