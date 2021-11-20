@extends('layouts.app_theme')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 mt-2">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="../user">Home</a></li>
					<li class="breadcrumb-item active" aria-current="page">Resources</li>
				</ol>
			</nav>
		</div>
		<div class="col-md-6 mt-4">
			{{ $resources->links('custom_pagination') }}
		</div>
	</div>
	@if(count($resources))
		<div class="row list-layout">
                <div class="resource-view-wrap">
				@foreach($resources as $resource)
					<div class="event-detail-list">
						<div class="col-12">
							<div class="row align-items-center">
								<div class="col-md-8 text-md-left text-center">
									<h5>{{$resource->title}}</h5>
									<p>{{$resource->description}}</p>
								</div>
								<div class="col-md-4 text-md-right text-center">
									<a href="{{$resource->url}}" target="_blank" class="btn btn-blue-bg">(Hyperlink)</a>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	@else
		<div class="col-12">
			<div class="row align-items-center">
				<div class="col-md-8 text-md-left text-center">
					<h5>Resources not found!.</h5>
				</div>
			</div>
		</div>
	@endif
</div>
@endsection