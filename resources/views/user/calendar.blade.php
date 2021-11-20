@extends('layouts.app_theme')

@section('content')
<div class="container">
    <div class="row">
  		<div class="col-md-6 mt-2">
  			<nav aria-label="breadcrumb">
  				<ol class="breadcrumb">
  					<li class="breadcrumb-item"><a href="../user">Home</a></li>
  					<li class="breadcrumb-item active" aria-current="page">Calendar</li>
  				</ol>
  			</nav>
  		</div>
  	</div>
  <div class="row text-center spinnerRow" style="display:none">
    <div class="col-md-12">
      <div class="spinner-grow text-secondary" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
  </div>
	<div class="calendar-view-wrap">
    <div id='calendar'></div>
	</div>
</div>
<script type="text/javascript">
  var eventDates = [];
</script>
         
@foreach($events as $date=>$eventVal)
<!-- Event list modal by date -->
<div class="modal fade" id="dateInfoModalCenter_{{ $date }}" tabindex="-1" role="dialog" aria-labelledby="dateInfoModalCenterTitle_{{ $date }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg events-date-popup" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dateInfoModalLongTitle_{{ $date }}"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @foreach($eventVal as $event)
          <script type="text/javascript">
            eventDates.push("{{ $event->event_start_datetime }}");
          </script>
          <div class="event-detail-list">
              <div class="col-12">
                  <div class="row">
                      <div class="col-md-8 text-md-left text-center">
                          <h5>{{ $event->event_title }}</h5>
                          <p class="events-detail-description">{{ $event->event_description }}</p>
                          <p class="events-detail-date">
                            Start Time&nbsp;&nbsp;:&nbsp;&nbsp;{{ date('d/m/Y g:i:s A', strtotime($event->event_start_datetime)) }}<br>
                            End Time&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;{{ date('d/m/Y g:i:s A', strtotime($event->event_end_datetime)) }}
                          </p>
                      </div>
                      <div class="col-md-4 text-md-right">
                          <p>{{ $event->event_location }}</p>
                          <p class="event-detail-link"><a href="{{ $event->event_link }}" target="_blank">Link</a></p>
                      </div>
                  </div>
              </div>
          </div>          
        @endforeach
      </div>      
    </div>
  </div>
</div>
@endforeach
      

@endsection
@section('script')
<script type="text/javascript" src="{{ asset('custom/theme/js/fullcalendar.js') }}"></script>
<script src="{{ asset('custom/adminLTE/plugins/moment/moment.min.js') }}"></script>
<script type="text/javascript">
var eventDatesFinalVal = [];
var eventDatesFiltered = [];
console.log(' --- Original --- ');
console.log(eventDates);
eventDates.forEach(function(i,v){
  eventDatesFiltered[v] = moment(i).format("Y-MM-DD");
});
console.log(' --- Converted --- ');
console.log(eventDatesFiltered);

eventDatesFiltered = eventDatesFiltered.filter (function (value, index, array) { 
    return array.indexOf (value) == index;
});
console.log(' --- Unique --- ');
console.log(eventDatesFiltered);

eventDatesFiltered.forEach(function(i,v){
  eventDatesFinalVal[v] = {};
  eventDatesFinalVal[v]['start'] = i;
});
console.log(' --- Final --- ');
console.log(eventDatesFinalVal);

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: '{{ $initialDate }}',
        headerToolbar: {
            left: 'title',            
        },
        height: 'auto',
        events: eventDatesFinalVal,
        eventClick: function(info) {
            console.log(info.event.start);
            var selectedDate = moment(info.event.start).format("DD");
            console.log(selectedDate);
            $('#dateInfoModalLongTitle_'+selectedDate).html(moment(info.event.start).format("D-M-Y"));
            $('#dateInfoModalCenter_'+selectedDate).modal('show');
        }
    });
    calendar.render();
});
$(document).ready(function () {
  $(".fc-next-button").on('click', function(){
    $(".calendar-view-wrap").css({ 'display': 'none' });
    $(".spinnerRow").css({ 'display': 'block' });
  	window.location = "{{ route('user-calendar', ['s'=>$next]) }}";
  });
  $(".fc-prev-button").on('click', function(){
    $(".calendar-view-wrap").css({ 'display': 'none' });
    $(".spinnerRow").css({ 'display': 'block' });
  	window.location = "{{ route('user-calendar', ['s'=>$previous]) }}";
  });  
});
</script>
@endsection