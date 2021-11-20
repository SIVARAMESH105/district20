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
                    <li class="breadcrumb-item"><a href="{{ route('manage-contractor-resources') }}">Manage Contractor Resources</a></li>
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
        <form role="form" id="add-contractor-resources" action="{{ route('manage-contractor-resources-update') }}" name="add-contractor-resources" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body row">
            <div class="col-12 form-group">
              <label for="title">Title<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="{{ old('title')?old('title'):$info->title }}">
            </div> 
            <div class="col-12 form-group">
              <label for="url">Url<span class="text-danger">*</span></label>
              <input type="url" class="form-control" id="url" name="url" placeholder="Enter Url" value="{{ old('url')?old('url'):$info->url }}">
            </div>   
            <div class="col-12 form-group">
              <label for="description">Description<span class="text-danger"></span></label>
              <textarea id="description" class="form-control" rows="4" name="description" placeholder="Enter Address">{{ old('description')?old('description'):$info->description }}</textarea>
            </div>          
            <div class="col-12 form-group">
              <label for="chapter">Chapter<span class="text-danger">*</span></label>
              <select type="chapter" class="form-control" id="chapter" name="chapter">
                <option value="">--- Select Chapter ---</option>
                @foreach($chapters as $chapter)
                  <option value="{{ $chapter->id }}" {{ $chapter->id==old('chapter') ? 'selected' : ($chapter->id==$info->chapter_id ? 'selected' : '') }}>{{ $chapter->chapter_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="card-footer">
            <input type="hidden" name="id" value="{{ $info->id }}">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('manage-contractor-resources') }}" class="btn btn-secondary">Cancel</a>
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
  $('#add-contractor-resources').validate({
    rules: {
      title: {
        required: true,
      },
      url: {
        required: true,
        url: true,
      },
      chapter: {
        required: true,
      },
    },
    messages: {
      title: {
        required: "Please enter a title",
      },
      url: {
        required: "Please enter a url",
        email: "Please enter a vaild url"
      },
      chapter: {
        required: "Please select a chapter",
      },
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