@extends('layouts.app_theme')

@section('content')
<section class="change-password">
  <div class="container">
      <nav aria-label="breadcrumb">
          <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="../user">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Settings</li>
          </ol>
      </nav>
      <div class="row">
          <div class="col-md-12">
              <div class="logo-login text-center change-iteam">
                  <img src="{{ asset('images/logo-slcc.png') }}">
              </div>
          </div>
         <div class="col-md-12 password-layout">
             <h5>Manage information about you, your in app and notification preferences</h5>
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
          <form class="form" action="{{ route('user-settings-post') }}" id="settings-form" method="post">
            @csrf
              <div class="form-group row form-group-border-btm">
                <label  class="col-md-3 col-sm-12 m-0 col-form-label">First Name</label>
                <div class="col-md-9 col-sm-12">
                  <input type="text" id="name" name="name" class="form-control border-none" value="{{ $user->name }}">
                  <div class="form-control-close"></div>
                </div>
              </div>
              <div class="form-group row form-group-border-btm">
                <label  class="col-md-3 col-sm-12 m-0 col-form-label">Last Name</label>
                <div class="col-md-9 col-sm-12">
                  <input type="text" id="last_name" name="last_name" class="form-control border-none" value="{{ $user->last_name }}">
                  <div class="form-control-close"></div>
                </div>
              </div>
              <div class="form-group row form-group-border-btm">
                  <label  class="col-md-3 col-sm-12 m-0 col-form-label">Email</label>
                  <div class="col-md-9 col-sm-12">
                    <input type="email" id="email" name="email" class="form-control border-none" value="{{ $user->email }}">
                    <div class="form-control-close"></div>
                  </div>
                </div>
                <div class="form-group row form-group-border-btm">
                  <label  class="col-md-3 col-sm-12 m-0 col-form-label">Primary Language</label>
                  <div class="col-md-9 col-sm-12">
                    <select id="language" name="language" class="custom-nice-select">
                      @foreach($languages as $language)
                        <option value="{{ $language->id }}" @if($user->primary_language==$language->id) selected @endif>{{ $language->name }}</option>
                      @endforeach                      
                    </select>
                  </div>
                </div>
                <div class="form-group row form-group-border-btm">
                  <label  class="col-md-3 col-sm-12 m-0 col-form-label">Font Size</label>
                  <div class="col-md-9 col-sm-12">
                    <select id="font_size" name="font_size" class="custom-nice-select">
                      @foreach($font_size as $id=>$size)
                        <option value="{{ $id }}" @if($user->font_size==$id) selected @endif>{{ $size }}</option>
                      @endforeach                      
                    </select>
                  </div>
                </div>
                <div class="form-group row form-group-border-btm">
                  <label  class="col-md-3 col-sm-12 m-0 col-form-label">Notifications</label>
                  <div class="col-md-9 col-sm-12">
                    <div class="group-toggle-wrappe">
                      @php
                        $active = $user->notification==1?"true":"false";
                      @endphp
                      <button type="button" onclick="notificationChanged(this);" class="btn btn-toggle" data-toggle="button" aria-pressed="{{ $active }}" autocomplete="off">
                        <div class="handle"></div>
                      </button>
                      <input type="hidden" id="notification" name="notification" value="{{ $user->notification }}">
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="offset-3 col-md-9 col-sm-12">
                    <input type="hidden" id="id" name="id" value="{{ $user->id }}">
                    <input type="hidden" id="chapter" name="chapter" value="{{ $user->chapter_id }}">
                    <input type="hidden" id="user_role" name="user_role" value="{{ $user->user_role }}">
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
  $('#settings-form').validate({
    rules: {
      name: {
        required: true,
      },
      last_name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      }    
    },
    messages: {
      name: {
        required: "Please enter a first name",
      },
      last_name: {
        required: "Please enter a last name",
      },
      email: {
        required: "Please enter a email address",
        email: "Please enter a vaild email address"
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
<script>
function notificationChanged(self){
  console.log($(self).hasClass( "active" ));
  if($(self).hasClass("active")) {
    $("#notification").val("0");
  } else {
    $("#notification").val("1");
  }
}
</script>
@endsection
@section('style')
<style>
.group-toggle-wrappe {
    position: initial;
    }
</style>
@endsection
