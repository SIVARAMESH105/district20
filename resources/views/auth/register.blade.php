@extends('layouts.app_theme_login')

@section('content')
<section class="register-page">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="logo-login text-center">
                    <img src="{{ asset('images/logo-login-page.png') }}">
                </div>
            </div>
            <div class="col-md-12">
                <form method="POST" class="register" id="register-form" action="{{ route('register') }}">
                @csrf
                    <div class="row">
                        <div class="col-md-12">
                            @if(session()->has('success'))  
                                <div class="alert alert-success"> {!! session('success') !!} </div>
                            @endif 
                            @if(session()->has('error')) 
                                <div class="alert alert-danger"> {!! session('error') !!} </div>  
                            @endif
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group ">
                                <label>Name</label>
                                <div class="form-group-field">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>
                                </div>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Companys</label>
                                <div class="form-group-field">
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}" >
                                </div>
                                @error('company_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email address</label>
                                <div class="form-group-field">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password</label>
                                <div class="form-group-field">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NECA#</label>
                                <div class="form-group-field">
                                    <input type="text" class="form-control @error('registration_number') is-invalid @enderror" id="registration_number" name="registration_number" value="{{ old('registration_number') }}" >
                                </div>
                                @error('registration_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Re-Enter Password</label>
                                <div class="form-group-field">
                                    <input  id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                </div>
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">                                
                                <div class="form-group-field form-group-field-select">
                                    <label>Select NECA Chapter</label>
                                    <select class="form-control custom-nice-select" id="chapter" name="chapter">
                                        <option value="">--- Select Chapter ---</option>
                                        @foreach($chapters as $chapter)
                                        <option value="{{ $chapter->id }}" {{ $chapter->id==old('chapter') ? 'selected' : ''}}>{{ $chapter->chapter_name }}</option>
                                        @endforeach
                                    </select>                                
                                </div>
                                @error('chapter')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="submit" type="submit">submit</button>
                            <p>Your account request will be reviewed and approved within 25 hours</p>
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