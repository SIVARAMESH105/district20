@extends('layouts.app_theme_login')

@section('content')
<section class="login-page">
    <div class="container height-full-page">
        <div class="row align-items-center height-full-page">
            <div class="col-12">
                <div class="logo-login text-center">
                    <img src="{{ asset('images/logo-login-page.png') }}">
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-6 login-content-wrap">
                        <div class="login-content">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus dignissim, lectus ut rutrum aliquet, massa ligula sodales leo, et tempor nunc massa non dolor. Suspendisse elementum neque elit, sit amet semper ante pulvinar in. Maecenas vitae scelerisque nulla, quis molestie ex. Nunc est urna, condimentum a neque eu, consequat finibus nulla. Aliquam consectetur congue neque eget elementum.</p>
                            <p>Fusce aliquet est eget libero malesuada accumsan. Phasellus id mi sed eros lobortis aliquam at nec lacus. Sed quis diam suscipit, fringilla metus eu, efficitur neque. Cras in egestas nisi, quis rutrum arcu. Nunc sed euismod nunc, et eleifend erat. Proin fermentum ornare purus id consectetur. Donec cursus tempus metus aliquet volutpat. Fusce id mi metus. Fusce bibendum magna a nisi convallis, sed vestibulum mauris vestibulum. Nam non justo at quam mattis porta. Pellentesque bibendum consequat pellentesque.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        
                        <div class="login-form">
                            <form method="POST" id="login-form" action="{{ route('login') }}">
                                @csrf
                                <div class="form-group">
                                    @if(session()->has('success'))    
                                        <div class="alert alert-success"> {!! session('success') !!} </div>
                                    @endif
                                    @if(session()->has('error')) 
                                        <div class="alert alert-danger"> {!! session('error') !!} </div>  
                                    @endif 
                                </div>
                                <div class="form-group">
                                    <label>Email address</label>
                                    <div class="form-group-field">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" autofocus>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="form-group-field">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="current-password">
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Log In</button>
                            </form>
                            <ul class="account-process-list">
                              <li><a href="{{ route('register') }}">Create an Account</a></li>
                              <li><a href="{{ route('password.request') }}">Forgot Password?</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="login-footer-list">
                  <ul>
                    <li><a href="{{ route('user-contact') }}">Contact Us</a></li>
                    <li><a href="{{ route('user-directory') }}">Directory</a></li>
                  </ul>
                </div>
            </div>
        </div>
</section>
@endsection