@extends('layouts.app_theme')

@section('content')
 <div class="container">
    <div class="row">
		<div class="col-md-6 mt-2">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="../user">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Announcements</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 mt-4">
			{{ $announcements->links('custom_pagination') }}
		</div>
	</div>
	@if(count($announcements))
    <div class="announcements-info-list-wrap">
        <ul id="announcements-info-list-data">
        	@foreach($announcements as $index=>$announcement)
	            <li class="row {{ $announcement->seen==0?'active':'' }}" id="AnnouncementRowActive-{{ $announcement->id }}">
	                <div class="col-md-8 text-center text-md-left">
	                    <h4>{{ $announcement->title }}</h4>
	                    <p>{{ date('d/m/Y', strtotime($announcement->created_at)) }}</p>
	                </div>
	                <div class="col-md-4 text-center text-md-right">
	                    <a href="javascript:void(0);" onclick="notificationAction({{ $announcement->id }});" data-toggle="modal" data-target="#announcement-{{ $index }}">View Details</a>
	                </div>
	            </li>
	            <!-- Modal -->
				<div class="modal fade" id="announcement-{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="announcement-{{ $index }}" aria-hidden="true">
				  <div class="modal-dialog" role="document">
				    <div class="modal-content">	      
				      <div class="modal-body">
				      	<div class="row">
				      		<div class="col-md-8 text-center text-md-left">
			                    <h4>{{ $announcement->title }}</h4>
			                    <p>{{ date('d/m/Y', strtotime($announcement->created_at)) }}</p>
			                </div>
			                <div class="col-md-4 text-center text-md-right">
			                	<a href="{{ $announcement->link }}" target="_blank" class="btn-sm btn-blue-bg">(Hyperlink)</a>
			                </div>
			            </div>
			            <hr>
	                    <p>{!! $announcement->description !!}</p>
			           </div>
				    </div>
				  </div>
				</div>
            @endforeach
        </ul>
    </div>    
   @else
   	<div class="col-12">
		<div class="row align-items-center">
			<div class="col-md-8 text-md-left text-center">
				<h5>Announcements not found!.</h5>
			</div>
		</div>
	</div>
   @endif
</div>
@endsection
@section('script')
<script>
function notificationAction(notification_id){
    $.ajax({
          type: "POST",
          dataType: 'json',
          data:{ notification_id: notification_id, _token:csrf_token },
          url: "{{ route('notificationAction') }}",
          success: function (data) {
            $("#AnnouncementRowActive-"+notification_id).removeClass("active");
          }
      });
}
</script>
@endsection
@section('style')
<style>
#announcements-info-list-data li:hover{
	background-color:rgb(255 255 255)!important;
}
#announcements-info-list-data li.row.active:hover{
	background-color: rgba(0, 102, 255, 0.05)!important;
}
</style>
@endsection