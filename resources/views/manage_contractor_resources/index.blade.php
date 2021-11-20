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
                    <div class="offset-8 col-sm-4 text-right" style="padding: 10px;">
                        <a href="{{ route('manage-contractor-resources-add') }}" class="btn btn-info btn-sm">Add Contractor Resource</a>
                    </div>                    
                    <table id="manage-contractor-resources" class="table table-bordered">
                        <thead>
                            <th>Title</th>
                            <th>Url</th>
                            <th>Action</th>
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
  $('#manage-contractor-resources').DataTable({
    "processing": false,
    "serverSide": true,
    "pageLength": 25,
    "searching":   true,
    "bPaginate": true,
    "bLengthChange": true,
    "bInfo" : false,
    "aaSorting": [],
    "order": [[ 0, "desc" ]],
    "ajax": "{{ route('getContractorResourcesListAjax') }}",
    "columns": [
                  {data: 'title', name: 'title'},
                  {data: 'url', name: 'url'},
                  {data: 'action', name: 'action', sortable: false},
                ]
  });
  //$('.dataTables_processing').html('<div class="lds-ripple"><div></div><div></div></div>');
  $(document).on('click', '.delete-resource', function(){
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
                        text: "Contractor Resource Deleted Successfully.",
                        type: "success",
                        confirmButtonColor: "#22D69D"
                    });
                      $('#manage-contractor-resources').DataTable().draw();
                    }
                  }
              });
        }
    });
  });
});
</script>
@endsection