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
                    <li class="breadcrumb-item"><a href="{{ route('manage-user') }}">{{ $brVal }}</a></li>
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
        <form role="form" id="add-user" action="{{ route('manage-user-update') }}" name="add-user" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="name">Name<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name')?old('name'):$info->name }}">
            </div>
            <div class="form-group">
              <label for="email">Email Address<span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ old('email')?old('email'):$info->email }}">
            </div>
            <div class="form-group">
              <label for="company_name">Company Name<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter Company Name" value="{{ old('company_name')?old('company_name'):$info->company_name }}">
            </div>
            @if(AdminHelper::checkUserIsSuperAdmin())
            <div class="form-group">
              <label for="chapter">Chapter<span class="text-danger">*</span></label>
              <select type="chapter" class="form-control" id="chapter" name="chapter">
                <option value="">--- Select Chapter --- </option>
                @foreach($chapters as $chapter)
                  <option value="{{ $chapter->id }}" {{ $chapter->id==old('chapter') ? 'selected' : ($info->chapter_id==$chapter->id ? 'selected' : '') }}>{{ $chapter->chapter_name }}</option>
                @endforeach
              </select>
            </div>
            @else
              <input type="hidden" name="chapter" value="{{ $info->chapter_id }}">
            @endif
            <div class="form-group">
              <label for="rnumber">Registration Number</label>
              <input type="rnumber" class="form-control" id="rnumber" name="rnumber" placeholder="Enter Registration Number" value="{{ old('rnumber')?old('rnumber'):$info->registration_number }}">
            </div>
            <div class="form-group">
              <label for="phone">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" value="{{ old('phone')?old('phone'):$info->phone }}">
            </div>
            @if(AdminHelper::checkUserIsSuperAdmin())
              <div class="form-group">
                <label for="user_role">User Role<span class="text-danger">*</span></label>
                <select type="user_role" class="form-control" id="user_role" name="user_role">
                  <option value="">--- Select User Role ---</option>
                  @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $role->id==old('user_role') ? 'selected' : ($role->id==$info->user_role ? 'selected' : '') }}>{{ $role->role_name }}</option>
                  @endforeach
                </select>
              </div>
            @else
              <input type="hidden" name="user_role" value="3">
            @endif
            <div class="form-group">
              <div class="col-xs-12 col-sm-6">
                <label for="profile_pic">Profile Pic</label>
                <div class="custom-file">  
                  <input type="file" name="profile_pic" class="custom-file-input" id="profile_pic" accept="image/*">
                  <label class="custom-file-label" for="profile_pic">Choose file</label>
                </div>
              </div>
              @if($info->profile_pic)
              <div class="col-xs-12 col-sm-6">              
                  <a href="{{ asset($info->profile_pic) }}" target="_blank">View Profile Picture</a>
              </div>
              @endif
            </div>            
            <div class="form-group clearfix">
                <div class="icheck-primary d-inline">
                  <input type="radio" id="radioPrimary1" name="status" value="1" {{ $info->status==1?'checked':'' }}>
                  <label for="radioPrimary1">Active</label>
                </div>
                <div class="icheck-danger d-inline">
                  <input type="radio" id="radioPrimary2" name="status" value="0" {{ $info->status==0?'checked':'' }}>
                  <label for="radioPrimary2">Inactive</label>
                </div>
              </div> 
          </div>
          <div class="card-footer">
            <input type="hidden" name="id" value="{{ $info->id }}">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('manage-user') }}" class="btn btn-secondary">Cancel</a>
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
  $('#add-user').validate({
    rules: {
      name: {
        required: true,
      },
      company_name: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
      phone: {
        minlength: 14,
      },
      chapter: {
        required: true,
      },
      // rnumber: {
      //   required: true,
      // },
      user_role: {
        required: true,
      },      
    },
    messages: {
      name: {
        required: "Please enter a name",
      },
      company_name: {
        required: "Please enter a company name",
      },
      email: {
        required: "Please enter a email address",
        email: "Please enter a vaild email address"
      },
      phone: {
        minlength: "Please enter a valid phone number"
      },
      chapter: {
        required: "Please select a chapter",
      },
      user_role: {
        required: "Please select a user role",
      },
      rnumber: {
        required: "Please enter a registration number",
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
});
</script>
@endsection