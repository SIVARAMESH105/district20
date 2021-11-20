@extends('layouts.app_theme')

@section('content')
<div class="container">
	<nav aria-label="breadcrumb">
	    <ol class="breadcrumb">
	        <li class="breadcrumb-item"><a href="../user">Home</a></li>
	        <li class="breadcrumb-item active" aria-current="page">Contact</li>
	    </ol>
	</nav>
	<div class="row">
	    <div class="col-md-12">
	        <div class="inner-contact-wrapper">
		        <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum pharetra purus at ligula pellentesque, nec luctus metus rutrum. Proin malesuada tincidunt odio, convallis egestas felis finibus quis. Nulla convallis ligula id metus feugiat, non consectetur ipsum vulputate. Fusce lobortis, felis ac convallis tristique, ex nisl ullamcorper est, vel interdum arcu erat ut felis. Sed eget sapien lacinia, facilisis elit vitae, egestas lectus. Nullam maximus ante at lacus finibus lobortis. Vivamus at molestie est. Proin tincidunt, velit molestie placerat, a elementum felis nulla ac odio. </p>
		        <div class="contact-page">
		        	@if(session()->has('success'))  
			            <div class="alert alert-success"> {!! session('success') !!} </div>
			        @endif
			        @if(session()->has('error')) 
			            <div class="alert alert-danger"> {!! session('error') !!} </div>  
			        @endif
			        <form class="form" action="{{ route('user-contact') }}" method="post" id="contact-form">
			        	@csrf
			            <div class="form-group">
			                <label>Name</label>
			                <div class="form-group-field">
			                    <input type="text" name="name" id="name" class="form-control">		                    
			                </div>
			            </div>
			            <div class="form-group">
			                <label>Email</label>
			                <div class="form-group-field">
			                    <input type="email" name="email" id="email" class="form-control">		                   
			                </div>
			            </div>
			            <div class="form-group">
			                <label>Message</label>
			                <textarea class="form-control" name="message" id="message" rows="11.5" ></textarea>
			              </div>
			            <button class="form-group-button btn btn-blue-bg" type="submit">Submit</button>
			        </form>
		        </div>
		    </div>
		</div>
	</div>
</div>
@endsection
@section('script')
<script src="{{ asset('custom/adminLTE/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
  $('#contact-form').validate({
    rules: {
      name: {
        required: true,
      },
      message: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      }    
    },
    messages: {
      name: {
        required: "Please enter a name",
      },
      message: {
        required: "Please enter a message",
      },
      email: {
        required: "Please enter a email address",
        email: "Please enter a vaild email address"
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
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