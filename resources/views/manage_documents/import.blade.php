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
                    <li class="breadcrumb-item"><a href="{{ route('manage-document') }}">Manage Documents</a></li>
                    <li class="breadcrumb-item active">Import</li>
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
        <form role="form" id="add-document" action="{{ route('manage-document-import') }}" accept-charset="utf-8" name="add-document" method="POST" enctype="multipart/form-data">
          @csrf 
          <div class="card-body row">       
            <div class="form-group col-12">
                <label for="chapter">Chapter</label>
                <select class="form-control" id="chapter" name="chapter">
                    <option value="">--- Select Chapter ---</option>
                    @foreach($chapters as $chapter)
                    <option value="{{ $chapter->id }}">{{ $chapter->chapter_name }}</option>
                    @endforeach
                   
                </select>
            </div>
            <div class="col-12 form-group">
                <label for="excel_file">Upload Excel</label>
                <div class="custom-file">  
                <input type="file" name="excel_file" class="custom-file-input" id="excel_file">
                <label class="custom-file-label" for="excel_file">Choose file</label>
                </div>
          </div>                              
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('manage-document') }}" class="btn btn-secondary">Cancel</a>
            <a class="text-right" href="{{ asset('sample-excel-files/Sample-Documents.xlsx') }}" style="float: right;"><i class="fas fa-download"></i> Sample File</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
@section('style')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script type="text/javascript" defer>
 
$(document).ready(function () {
   $('#add-document').validate({
    rules: {
      chapter: {
        required: true,
      },
      excel_file: {
        required: true,
        //accept: "doc,csv,xlsx,xls,docx"
        extension: "doc|csv|xlsx|xls|docx|doc|pdf"
        
      }
    },
    messages: {
      chapter: {
        required: "Please select a chapter",
      },
      excel_file: {
        required: "Please upload a document",
        extension: "Only pdf,csv,doc,xls,xlsx format are allowed"
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