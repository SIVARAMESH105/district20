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
                    <li class="breadcrumb-item"><a href="{{ route('manage-event') }}">Manage Events</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
  <div class="row">
    <div class="col-md-8">
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
        <div class="card-body">
         <form role="form" id="add-event" name="add-event" method="POST" action="{{ route('manage-event-update') }}">
            @csrf
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="event_title">Event Title<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="event_title" name="event_title" value="{{ old('event_title')?old('event_title'):($info->event_title?$info->event_title:'') }}">
                </div>
                </div>
                <div class="col-sm-6">                  
                  <div class="form-group">
                    <label for="event_location">Event Location<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="event_location" name="event_location" value="{{ old('event_location')?old('event_location'):($info->event_location?$info->event_location:'') }}">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                    <label for="event_start_date">Event Start Date<span class="text-danger">*</span></label>
                    <div class="input-group date" id="event_start_date" data-target-input="nearest">
                      <input type="text" class="form-control datetimepicker-input" data-target="#event_start_date" data-toggle="datetimepicker" id="event_start_date_val" name="event_start_date_val" autocomplete="off" value="{{ old('event_start_date_val')?old('event_start_date_val'):($info->event_start_datetime?$info->event_start_datetime:'') }}">
                    </div>
                  </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="event_end_date">Event End Date<span class="text-danger">*</span></label>
                  <div class="input-group date" id="event_end_date" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#event_end_date" data-toggle="datetimepicker" name="event_end_date_val" id="event_end_date_val" autocomplete="off" value="{{ old('event_end_date_val')?old('event_end_date_val'):($info->event_end_datetime?$info->event_end_datetime:'') }}">
                  </div>
                </div>
              </div>
              @if(AdminHelper::checkUserIsSuperAdmin())
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="chapter">Chapter<span class="text-danger">*</span></label>
                  <select type="chapter" class="form-control" id="chapter" name="chapter">
                    <option value="">--- Select Chapter ---</option>
                    @foreach($chapters as $chapter)
                      <option value="{{ $chapter->id }}" {{ old('chapter')==$chapter->id? 'selected' : ((!old('chapter') && $chapter->id==$info->chapter_id) ? 'selected' : '') }}>{{ $chapter->chapter_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              @else
                <input type="hidden" name="chapter" id="chapter" value="{{ Auth::user()->chapter_id }}">
              @endif
            </div>
            <div class="row">
              <div class="col-sm-8">
                <div class="form-group">
                  <label for="event_link">Event Link</label>
                  <input type="text" class="form-control" id="event_link" name="event_link" value="{{ old('event_link')?old('event_link'):($info->event_link?$info->event_link:'') }}">
                </div>
                </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                    <label for="event_description">Event Description<span class="text-danger">*</span></label>
                    <textarea name="event_description" id="event_description" class="form-control">{{ old('event_description')?old('event_description'):($info->event_description?$info->event_description:'') }}</textarea>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group clearfix">
                    <label for="event_description">Status&nbsp;&nbsp;&nbsp;</label>
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
            </div>
            <div class="form-group">
              <input type="hidden" name="id" value="{{ $info->id}}">
              <button type="submit" class="btn btn-primary" id="submit">Submit</button>
              <button type="button" class="btn btn-default" onclick="var urlVal = '{{ route("manage-event") }}'; return window.location=urlVal;">Close</button>
            </div>
          </form>
          </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('script')
<script type="text/javascript">
$(document).ready(function () {
  $('#add-event').validate({
    rules: {
      event_title: {
        required: true,
      },
      event_link: {
        url: true
      },
      event_location: {
        required: true,
      },
      event_start_date_val: {
        required: true,
      },
      event_end_date_val: {
        required: true,
      },      
      event_description: {
        required: true,
      },
      chapter: {
        required: true,
      },
    },
    messages: {
      event_title: {
        required: "Please enter a event title",
      },
      event_link: {
        url: "Please enter valid event link",
      },
      event_location: {
        required: "Please enter a event location",
      },
      event_start_date_val: {
        required: "Please select a event start date",
      },
      event_end_date_val: {
        required: "Please select a event end date",
      },
      event_description: {
        required: "Please enter a event description",
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
    },
    submitHandler: function(form) {
      return true;
    }
  });
var today = new Date(); 
today.setHours(0, 0, 0, 0);
 $('#event_start_date').datetimepicker({
      sideBySide: true,
      date: new Date("{{ old('event_start_date_val')?old('event_start_date_val'):($info->event_start_datetime?$info->event_start_datetime:'') }}"),
      minDate:today,
      autoclose: true,
      useCurrent:false,
      closeOnDateSelect: true,
  });
 $("#event_start_date").on('change.datetimepicker', function (e) {
    var sdate = new Date(e.date._d);
    $('#event_end_date').datetimepicker('minDate', sdate);
  });
 $('#event_end_date').datetimepicker({
      sideBySide: true,
      date: new Date("{{ old('event_end_date_val')?old('event_end_date_val'):($info->event_end_datetime?$info->event_end_datetime:'') }}"),
      minDate:new Date(),
      autoclose: true,
      useCurrent:false,
      closeOnDateSelect: true,
  });
 $("#event_end_date").on('change.datetimepicker', function (e) {
    var edate = new Date(e.date._d);
    $('#event_start_date').datetimepicker('maxDate', edate);           
  });
});
</script>
@endsection