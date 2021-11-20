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
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
  <div class="row">        
    <div class="col-lg-12 col-12">
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
      <div class="table-responsive">
        <div class="offset-8 col-sm-4 text-right" style="padding: 10px;"><a href="javascript:void(0);" onclick="return $('#modal-add-event').modal('show');" class="btn btn-info btn-sm">{{ __('Add Event') }}</a> <a href="{{ route('manage-event-view-events') }}" class="btn btn-info btn-sm">{{ __('View Calendar') }}</a></div>
        <table id="manage-event-add" class="table table-bordered">
          <thead>
            <th>Title</th>
            <th>Location</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>chapter</th>
            <th>Status</th>
            <th>Action</th>
          </thead>
          <tbody>                     
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-add-event" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Event</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form role="form" id="add-event" action="" name="add-event" method="POST" onsubmit="return false">
            @csrf
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="event_title">Event Title<span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="event_title" name="event_title">
                </div>
                </div>
                <div class="col-sm-6">                  
                  <div class="form-group">
                    <label for="event_location">Event Location<span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="event_location" name="event_location">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                  <div class="form-group">
                    <label for="event_start_date">Event Start Date<span class="text-danger">*</span></label>
                    <div class="input-group date" id="event_start_date" data-target-input="nearest">
                      <input type="text" class="form-control datetimepicker-input" data-target="#event_start_date" data-toggle="datetimepicker" id="event_start_date_val" name="event_start_date_val" autocomplete="off">
                    </div>
                  </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="event_end_date">Event End Date<span class="text-danger">*</span></label>
                  <div class="input-group date" id="event_end_date" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#event_end_date" data-toggle="datetimepicker" name="event_end_date_val" id="event_end_date_val" autocomplete="off">
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
                        <option value="{{ $chapter->id }}" {{ $chapter->id==old('chapter') ? 'selected' : ''}}>{{ $chapter->chapter_name }}</option>
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
                  <input type="url" class="form-control" id="event_link" name="event_link">
                </div>
                </div>
            </div>   
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                    <label for="event_description">Event Description<span class="text-danger">*</span></label>
                    <textarea name="event_description" id="event_description" class="form-control"></textarea>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group clearfix">
                  <label for="event_description">Status&nbsp;&nbsp;&nbsp;</label>
                  <div class="icheck-primary d-inline">
                    <input type="radio" id="radioPrimary1" name="status" checked="" value="1">
                    <label for="radioPrimary1">Active</label>
                  </div>
                  <div class="icheck-danger d-inline">
                    <input type="radio" id="radioPrimary2" name="status" value="0">
                    <label for="radioPrimary2">Inactive</label>
                  </div>
                </div>
              </div>
            </div>               
            <div class="form-group">
              <button type="submit" class="btn btn-primary" id="submit">Submit</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('custom/datatables/css/dataTables.bootstrap4.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('custom/datatables_custom.css') }}">
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('custom/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.bootstrap4.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.rowReorder.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.scroller.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/datatables_custom.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {   
  //$('.dataTables_processing').html('<div class="lds-ripple"><div></div><div></div></div>');
  $('#manage-event-add').DataTable({
    "processing": false,
    "serverSide": true,
    "pageLength": 25,
    "searching":   true,
    "bPaginate": true,
    "bLengthChange": true,
    "bInfo" : false,
    "aaSorting": [],
    "order": [[ 0, "desc" ]],
    "ajax": "{{ route('getEventListAjax') }}",
    "columns": [
                    {data: 'event_title', name: 'event_title'},
                    {data: 'event_location', name: 'event_location'},
                    {data: 'event_start_datetime', name: 'event_start_datetime'},
                    {data: 'event_end_datetime', name: 'event_end_datetime'},
                    {data: 'chapter_name', name: 'chapter_name'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', sortable: false},
                  ]
  });
 
  $(document).on('click', '.delete-event', function(){
    var id = $(this).attr('data-id');
    var del_url = $(this).attr('data-url');
    swal({
        title: 'Are you sure?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22D69D',
        cancelButtonColor: '#FB8678',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn',
        cancelButtonClass: 'btn',
    }).then(function (result) {
       if (result.value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
                  type: "DELETE",
                  dataType: 'json',
                  url: del_url,
                  success: function (data) {
                    if(data){
                       swal({
                        title: "Success",
                        text: "Deleted Successfully.",
                        type: "success",
                        confirmButtonColor: "#22D69D"
                    });
                      $('#manage-event-add').DataTable().draw();
                    }
                  }
              });
        }
    });
  });
  var today = new Date(); 
  today.setHours(0, 0, 0, 0);
   $('#event_start_date').datetimepicker({
        sideBySide: true,
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
      addEvent();
    }
  });
});
function addEvent() {
  $('#submit').attr('disabled', 'disabled');
  var event_title = $("#event_title").val();
  var event_location = $("#event_location").val();
  var event_start_date_val = $("#event_start_date_val").val();
  var event_end_date_val = $("#event_end_date_val").val();
  var event_description = $("#event_description").val();
  var chapter = $("#chapter").val();
  var status = document.getElementById('radioPrimary1').checked?1:0;
  var event_link = $("#event_link").val();
  if(event_title && event_location && event_start_date_val && event_end_date_val && event_description && chapter) {
    $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
    $.ajax({
          type: "POST",
          dataType: 'json',
          data: { 
                  event_title:event_title,
                  event_location:event_location,
                  event_start_date_val:event_start_date_val,
                  event_end_date_val:event_end_date_val,
                  event_description:event_description,
                  chapter:chapter,
                  status:status,
                  event_link:event_link,
                  _token:csrf_token,
                  isAjax:1
                },
          url: '{{ route("manage-event-save") }}',
          success: function (data) {
            console.log(data);
            if(data && data.status==1){
               swal({
                  title: "Success",
                  text: "Event Created Successfully.",
                  type: "success",
                  confirmButtonColor: "#22D69D"
              }).then(function() {
                  window.location = '{{ route("manage-event") }}';
              });
            }
          }
      });
  } else {
    $('#submit').removeAttr('disabled');
    toastr.error('Something went wrong!.');
  }
}
</script>
@endsection