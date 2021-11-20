@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('manage-notification') }}">Manage Announcement</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
  <div class="row">
    <div class="col-md-6">
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
        @endif @if(session()->has('error')) 
            <div class="alert alert-danger"> {!! session('error') !!} </div>  
        @endif
      <div class="card card-primary">
        <form role="form" id="add-notification" action="{{ route('manage-notification-save') }}" name="add-notification" method="POST">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="title">Title<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="{{ old('title') }}">
            </div> 
            <div class="form-group">
              <label for="desc">Description<span class="text-danger">*</span></label>
              <textarea rows="10" cols="10" class="form-control" id="desc" name="desc"  placeholder="Enter description" >{{ old('desc') }}</textarea>
            </div>
            <div class="form-group">
              <label for="link">Link<span class="text-danger">*</span></label>
              <input type="url" class="form-control" id="link" name="link" placeholder="Enter link" value="{{ old('link') }}">
            </div>                    
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('manage-notification') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function () {
  $('#add-notification').validate({
    rules: {
      title: {
        required: true,
      },
      desc: {
        required: true,
      },
      link: {
        required: true,
      }
    },
    messages: {
      title: {
        required: "Please enter title",
      },
      desc: {
        required: "Please enter description",
      },
      link: {
        required: "Please enter link",
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