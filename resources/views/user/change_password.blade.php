@extends('layouts.app_theme')

@section('content')
<section class="change-password">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="../user">Home</a></li>
                    <li class="breadcrumb-item"><a href="../user/settings">Setting</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-md-12">
                    <div class="logo-login text-center change-iteam">
                        <img src="{{ asset('images/logo-slcc.png') }}">
                    </div>
                </div>
               <div class="col-md-12 password-layout">
                   <h5>Change Your Password below:</h5>
                   <div class="form-width-wrapper">
                   <div class="group-form-double-border">
                    @if (count($errors) > 0)
                      <div class="alert alert-danger">
                        <ul>
                          @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                    @endif 
                    @if(session()->has('success'))  
                        <div class="alert alert-success"> {!! session('success') !!} </div>
                    @endif
                    @if(session()->has('error')) 
                        <div class="alert alert-danger"> {!! session('error') !!} </div>  
                    @endif
                  <form class="form" action="{{ route('user-change-password') }}" id="change-password-form" name="change-password-form" method="post">
                      @csrf
                      <div class="form-group row form-group-border-btm">
                        <label  class="col-md-3 col-sm-12 m-0 col-form-label">Current Password</label>
                        <div class="col-md-9 col-sm-12">
                          <input type="password" name="password" id="password" class="form-control border-none">
                          <div class="form-control-close"></div>
                        </div>
                      </div>           
                      <div class="form-group row form-group-border-btm">
                        <label  class="col-md-3 col-sm-12 m-0 col-form-label">New Password</label>
                        <div class="col-md-9 col-sm-12">
                          <input type="password" name="new_password" id="new_password" class="form-control border-none"  >
                          <div class="form-control-close"></div>
                        </div>
                      </div>
                      <div class="form-group row form-group-border-btm">
                          <label  class="col-md-3 col-sm-12 m-0 col-form-label">Confirm New Password</label>
                          <div class="col-md-9 col-sm-12">
                            <input type="password" name="c_password" id="c_password" class="form-control border-none"  >
                            <div class="form-control-close"></div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="offset-3 col-md-9 col-sm-12">
                            <button class="form-group-button btn btn-blue-bg" type="submit">Update</button>
                          </div>
                        </div>                        
                    </form>
                </div>
                </div>
               </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
<script src="{{ asset('custom/adminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
  $('#change-password-form').validate({
    rules: {
      password: {
        required: true,
      },
      new_password: {
        required: true,
      },
      c_password: {
        required: true,
        equalTo: "#new_password"
      }    
    },
    messages: {
      password: {
        required: "Please enter a password",
      },
      new_password: {
        required: "Please enter a new password",
      },
      c_password: {
        required: "Please enter a confirm new password",
        equalTo: "New password and confirm new password should be same"
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.col-sm-12').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
@endsection