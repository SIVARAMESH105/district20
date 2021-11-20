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
                    <li class="breadcrumb-item"><a href="{{ route('manage-document-type') }}">Manage Document Types</a></li>
                    <li class="breadcrumb-item active">Edit</li>
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
        <form role="form" id="add-document-type" action="{{ route('manage-document-type-update') }}" name="add-document-type" method="POST">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="name">Name<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name')?old('name'):$info->document_type_name }}">
            </div>
          </div>
          <div class="card-footer">
            <input type="hidden" name="id" value="{{ $info->id }}">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('manage-document-type') }}" class="btn btn-secondary">Cancel</a>
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
  $('#add-document-type').validate({
    rules: {
      name: {
        required: true,
      }     
    },
    messages: {
      name: {
        required: "Please enter a document type name",
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