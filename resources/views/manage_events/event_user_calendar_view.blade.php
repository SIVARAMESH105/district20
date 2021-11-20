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
                    <li class="breadcrumb-item"><a href="{{ route('view-user-events') }}">{{ $title }}</a></li>
                    <li class="breadcrumb-item active">View Calendar</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
  <div class="row">        
    <div class="col-lg-12 col-12">
      <section class="content">
        <div class="container-fluid">
          <div class="row">           
            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-body p-0">
                  <div id="calendar"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
@endsection
@section('style')
  <link rel="stylesheet" href="{{ asset('custom/adminLTE/plugins/fullcalendar/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('custom/adminLTE/plugins/fullcalendar-daygrid/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('custom/adminLTE/plugins/fullcalendar-timegrid/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('custom/adminLTE/plugins/fullcalendar-bootstrap/main.min.css') }}">
  <link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
@endsection
@section('script')
<script src="{{ asset('custom/adminLTE/plugins/fullcalendar/main.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/fullcalendar-daygrid/main.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/fullcalendar-timegrid/main.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/fullcalendar-interaction/main.min.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/fullcalendar-bootstrap/main.min.js') }}"></script>
<script src='https://unpkg.com/popper.js/dist/umd/popper.min.js'></script>
<script src='https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js'></script>
<script>
  $(function () {
    var Calendar = FullCalendar.Calendar;
    var calendarEl = document.getElementById('calendar');
    var calendar = new Calendar(calendarEl, {
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      eventRender: function(info) {
        var tooltip = new Tooltip(info.el, {
          title: info.event.extendedProps.description,
          placement: 'top',
          trigger: 'hover',
          container: 'body'
        });
      },
      events    : {!! $events !!},
      eventRender: function(info) {
        var tooltip = new Tooltip(info.el, {
          title: info.event.extendedProps.description,
          placement: 'top',
          trigger: 'hover',
          container: 'body'
        });
      },
      eventClick: function(event) {
        //alert();
      },
      dateClick: function(info) {              
      },
      dateMouseEnter: function(info) {
        
      },
      eventMouseEnter:function(info){
        //alert(info.el.innerText);
      },
      editable  : false,
      droppable : true,
      drop      : function(info) {
        console.log(info);
      }    
    });
    calendar.render();    
  })  
</script>    
@endsection