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
                    <table id="manage-contractor-directories" class="table table-bordered">
                        <thead>
                            <th>Name</th>
                            <th>Position</th>
                          <!--   <th>Address</th> -->
                            <th>State</th>
                            <!-- <th>Zipcode</th> -->
                            <th>Phone</th>
                            <!-- <th>Fax</th> -->
                            <th>Email</th>
                            <!-- <th>Website</th> -->
                            
                        </thead>
                        <tbody>                     
                        </tbody>
                    </table>
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
  $('#manage-contractor-directories').DataTable({
    "processing": false,
    "serverSide": true,
    "pageLength": 25,
    "searching":   true,
    "bPaginate": true,
    "bLengthChange": true,
    "bInfo" : false,
    "aaSorting": [],
    "order": [[ 0, "desc" ]],
    "ajax": "{{ route('getContractordirectoriesListAjaxUserView') }}",
    "columns": [
                  {data: 'name', name: 'name'},
                  {data: 'position', name: 'position'},
                 /* {data: 'address', name: 'address'},*/
                  {data: 'state', name: 'state'},
                 /* {data: 'zipcode', name: 'zipcode'},*/
                  {data: 'phone', name: 'phone'},
                  /*{data: 'fax', name: 'fax'},*/
                  {data: 'email', name: 'email'},
                  /*{data: 'website', name: 'website'},*/
                ]
  });
  //$('.dataTables_processing').html('<div class="lds-ripple"><div></div><div></div></div>');
  
});
</script>
@endsection