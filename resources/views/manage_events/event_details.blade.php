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
                    <li class="breadcrumb-item"><a href="{{ $brLink }}">{{ AdminHelper::checkUserIsOnlyUserAdmin()?'Events':'Manage Events' }}</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
  <div class="row">
    <div class="col-md-8">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">{{ $info->event_title }}</h3>
        </div>
        <div class="card-body">
          <strong><i class="fas fa-book mr-1"></i> Chapter</strong>
          <p class="text-muted">
            {{ $chapter->chapter_name }}
          </p>
          <hr>
          <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
          <p class="text-muted">{{ $info->event_location }}</p>
          <hr>
          <strong><i class="fas fa-calendar-week"></i> Date</strong>
          <p class="text-muted"> {{ $info->event_start_datetime }} - {{ $info->event_end_datetime }}
          </p>
          <hr>
          @if($info->event_link)
          <strong><i class="fas fa-external-link-alt"></i> Event Link</strong>
          <p class="text-muted">
              <a href="{!! $info->event_link !!}" target="_blank">Click Here</a>
          </p>
          <hr>
          @endif
          <strong><i class="far fa-file-alt mr-1"></i> Description</strong>
          <p class="text-muted">{!! $info->event_description !!}</p>
          <hr>
          <strong><i class="fas fa-thermometer-three-quarters"></i> Status</strong><br>
          <p class="text-muted">{!! $info->status?"<span class='text text-info'>Active</span>":"<span class='text text-danger'>Inactive</span>" !!}</p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection