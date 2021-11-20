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
            <div class="col-md-7">
                <form method="POST" class="register" action="{{ route('verification.resend') }}">
                 @csrf
                    <div class="row">
                        <div class="col-md-12">                            
                            <p class="text-center">Verify Your Email Address</p>
                        </div>                        
                        <div class="col-md-12">
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif
                            {{ __('Before proceeding, please check your email for a verification link.') }}<br>
                            {{ __('If you did not receive the email') }},
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline submit">{{ __('click here to request another') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection