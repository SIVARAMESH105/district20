@extends('layouts.app_theme')

@section('content')
<div class="container">
	<div class="row">
	    <div class="sidebar-filter col-md-3">
	        <h5>FILTERS</h5>
	        <form id="formFilter" action="" method="GET">		        
		        <div class="sidebar-filter-list">
		            <div class="sidebar-filter-select">
		                <select id="type" name="type" class="custom-nice-select" onchange="submitForm()">
							<option value="">All Directory</option>
							<option value="1" @if($type==1) selected @endif>Contractor Directory</option>
							<option value="2" @if($type==2) selected @endif>JATC Directory</option>
							<option value="4" @if($type==4) selected @endif>Chapter Directory</option>
							<option value="3" @if($type==3) selected @endif>IBEW Directory</option>
		                </select>
		            </div>
		            <div class="sidebar-filter-select">
		                <select id="chapter" name="chapter" class="custom-nice-select" onchange="submitForm()">
		                    <option value="">All Chapters</option>
		                    @foreach($chapters as $chapter)
		                    	<option value="{{ $chapter->id }}" @if($current_chapter==$chapter->id) selected @endif>{{ $chapter->chapter_name }}</option>
		                    @endforeach
		                </select>
		            </div>
		        </div>
		        <input type="hidden" name="page" value="1">
	    	</form>
	    </div>
	    <div class="col-md-9 sidebar-main-content">
	        <div class="row">
	            <div class="col-md-6">
	                <nav aria-label="breadcrumb">
	                    <ol class="breadcrumb">
	                        <li class="breadcrumb-item"><a href="../user">Home</a></li>
	                        <li class="breadcrumb-item active" aria-current="page">Directories</li>
	                    </ol>
	                </nav>
	            </div>
	            <div class="col-md-6 text-md-right">
	            	{{ $directories->appends(['type' => $type, 'chapter' => $current_chapter ])->links('custom_pagination') }}
	            </div>
	        </div>
	        <div class="row members-group-list">	        	
	            @if(count($directories))
	            	@foreach($directories as $directory)
			            <div class="col-md-6">
			                <div class="group-members-wrapper">
			                    <div class="members-image-wrapper1">
									@if($directory->profile_pic)
										<img class="rounded-circle" onerror="onErrorFunction(this)" src="{{ asset($directory->profile_pic) }}">
									@else
										<img class="rounded-circle" src="{{ asset('images/thumbnail.svg') }}">
									@endif
			                    </div>
			                    <div class="members-content-wrapper">
			                        <div class="member-iteam">
			                        	@if($directory->name)
			                        		<h4>{{ $directory->name }}</h4>
			                        	@elseif($directory->contact)
			                        		<h4>{{ $directory->contact }}</h4>
			                        	@endif			                           
										<p>
										@if($directory->contact) {{ $directory->contact }} </br>@endif
										@if($directory->position) {{ $directory->position }} </br>@endif
										{{ $directory->address }}</br>
										{{ $directory->city }} {{ $directory->state }} {{ $directory->zipcode }}
										</p>
			                           @if($directory->phone)
			                           	<p>phone:<span class="group-of-communication"><a href="tel:{{ $directory->phone }}">{{ $directory->phone }}</a></span></p>
			                           @endif
			                           @if($directory->fax)
			                           	<p>Fax:<span class="group-of-communication"><a href="fax:{{ $directory->fax }}">{{ $directory->fax }}</a></span></p>
			                           @endif
			                           <div class="member-blue-button">
				                           @if($directory->email)
				                           	<a class="btn btn-blue-bg members-link-color" href="mailto:{{ $directory->email }}">Email</a>
				                           @endif
				                           @if($directory->website)
				                           	<a class="btn btn-blue-bg members-link-color" target="_blank" href="//{{ remove_http($directory->website) }}">Website</a>
				                           @endif
			                           </div>
			                        </div>
			                    </div>
			                </div>
			            </div>
		            @endforeach
	            @else
	            <div class="text-center select-directory">
                    <p>Directories not found!.</p>
                </div>
                @endif
	        </div>
	        <div class="row">
	            <div class="offset-6 col-md-6 text-md-right">
	            	{{ $directories->appends(['type' => $type, 'chapter' => $current_chapter ])->links('custom_pagination') }}
	            </div>
	        </div>
	    </div>
	</div>
	</div>
<?php 
function remove_http($url) {
   $disallowed = array('http://', 'https://');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         return str_replace($d, '', $url);
      }
   }
   return $url;
}
?>
@endsection
@section('script')
<script>
function submitForm(){
	$('#formFilter').submit();
}
function onErrorFunction(self){
	self.src= "{{ asset('images/thumbnail.svg') }}";
}
</script>
@endsection