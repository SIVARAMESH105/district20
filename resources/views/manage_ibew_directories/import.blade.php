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
                    <li class="breadcrumb-item"><a href="{{ route('manage-ibew-directories') }}">Manage Ibew Directory</a></li>
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
        <form role="form" id="add-directory" action="{{ route('manage-ibew-directories-import') }}" name="add-directory" method="POST" enctype="multipart/form-data">
          @csrf 
          <div class="card-body row">            
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
            <a href="{{ route('manage-ibew-directories') }}" class="btn btn-secondary">Cancel</a>
            <a class="text-right" href="{{ asset('sample-excel-files/Sample-ibew-Directory-Data-File.xlsx') }}" style="float: right;"><i class="fas fa-download"></i> Sample File</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection