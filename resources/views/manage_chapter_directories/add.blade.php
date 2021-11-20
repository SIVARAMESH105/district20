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
                    <li class="breadcrumb-item"><a href="{{ route('manage-chapter-directories') }}">Manage Chapter Directory</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
  <div class="row">
    <div class="col-md-12">
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
        <form role="form" id="add-directory" action="{{ route('manage-chapter-directories-save') }}" name="add-directory" method="POST" enctype="multipart/form-data">
          @csrf 
          <div class="card-body row">
            <div class="col-6 form-group">
              <label for="name">Company Name<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter Company Name" value="{{ old('name') }}">
            </div>
            <div class="col-6 form-group">
              <label for="email">Email Address<span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{ old('email') }}">
            </div>
            @if(AdminHelper::checkUserIsSuperAdmin())
            <div class="col-6 form-group">
              <label for="chapter">Chapter<span class="text-danger">*</span></label>
              <select type="chapter" class="form-control" id="chapter" name="chapter">
                <option value="">--- Select Chapter ---</option>
                @foreach($chapters as $chapter)
                  <option value="{{ $chapter->id }}" {{ $chapter->id==old('chapter') ? 'selected' : ''}}>{{ $chapter->chapter_name }}</option>
                @endforeach
              </select>
            </div>
            @else
               <input type="hidden" class="form-control" id="chapter" name="chapter" value="{{ Auth::user()->chapter_id }}">
            @endif
            <div class="col-6 form-group">
              <label for="position">Position<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="position" name="position" placeholder="Enter Position" value="{{ old('name') }}">
            </div>
            <div class="col-6 form-group">
              <label for="address">Address<span class="text-danger"></span></label>
              <textarea id="address" class="form-control" rows="4" name="address" placeholder="Enter Address" value="{{ old('address') }}"></textarea>
            </div>
            <div class="col-6 form-group">
              <label for="state">State<span class="text-danger"></span></label>
              <input type="text" class="form-control" id="state" name="state" placeholder="Enter State" value="{{ old('state') }}">
            </div>
            <div class="col-6 form-group">
              <label for="city">City<span class="text-danger"></span></label>
              <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" value="{{ old('city') }}">
            </div>
            <div class="col-6 form-group">
              <label for="zipcode">Zipcode<span class="text-danger"></span></label>
              <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Enter Zipcode" value="{{ old('zipcode') }}">
            </div>
            <div class="col-6 form-group">
              <label for="contact">Contact<span class="text-danger"></span></label>
              <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter Contact" value="{{ old('contact') }}">
            </div>
            <div class="col-6 form-group">
              <label for="phone">Phone<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone" value="{{ old('phone') }}">
            </div>
            <div class="col-6 form-group">
              <label for="fax">Fax<span class="text-danger"></span></label>
              <input type="text" class="form-control" id="fax" name="fax" placeholder="Enter Fax" value="{{ old('fax') }}">
            </div>                       
            <div class="col-6 form-group">
              <label for="website">Website</label>
              <input type="url" class="form-control" id="website" name="website" placeholder="Enter Website" value="{{ old('website') }}">
            </div>
            <div class="col-6 form-group">
              <label for="profile_pic">Profile Pic</label>
              <div class="custom-file">  
              <input type="file" name="profile_pic" class="custom-file-input" id="profile_pic" accept="image/*">
              <label class="custom-file-label" for="profile_pic">Choose file</label>
              </div>
            </div>                                     
          </div>
          <div class="card-footer">
            <input type="hidden" value="4" name="type">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('manage-chapter-directories') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/jquery.mask.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function () {
  $('#add-directory').validate({
    rules: {
      name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      chapter: {
        required: true,
      },
      position: {
        required: true,
      },
      phone: {
        required: true,
      },
          
    },
    messages: {
      name: {
        required: "Please enter a name",
      },
      email: {
        required: "Please enter a email address",
        email: "Please enter a vaild email address"
      },
      chapter: {
        required: "Please select a chapter",
      },
      position: {
        required: "Please enter a position",
      },
      phone: {
        required: "Please enter a phone",
        number: "Please enter the valid phone number",
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
$(document).ready(function () {
  $('#phone').mask('(000) 000-0000');
  //$('#contact').mask('(000) 000-0000');
});
</script>
@endsection