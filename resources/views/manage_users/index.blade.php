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
        <div class="offset-8 col-sm-4 text-right" style="padding: 10px;"><a href="{{ route('manage-user-add') }}" class="btn btn-info btn-sm">{{ $addText }}</a></div>
        @if(AdminHelper::checkUserIsSuperAdmin())
        <div class="offset-8 col-sm-4 text-right">
          <div class="row">
            <div class="col-sm-6">Role Filter:</div>
            <div class="col-sm-6">
              <form method="get" class="" role="form">
                    <div class="form-group">                  
                        <select class="form-control form-control-sm" name="r" id="r" onchange="this.form.submit();">
                          <option value="">---All---</option>
                          @foreach($roles as $key=>$val)
                            <option value="{{ $val['id'] }}" {{ $role == $val['id'] ? 'selected':''}}>{{ $val['role_name'] }}</option>
                          @endforeach
                        </select>
                    </div>
                </form>
              </div>
            </div>
        </div>
        @endif
        <table id="manage-users" class="table table-bordered">
          <thead>
            <th>Name</th>
            <th>Email</th>
            @if(AdminHelper::checkUserIsSuperAdmin())
              <th>Role</th>
            @endif
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
@if(AdminHelper::checkUserIsSuperAdmin())
  <script type="text/javascript">
    var thData = [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'role_name', name: 'role_name'},
                    {data: 'status', name: 'status'},                  
                    {data: 'action', name: 'action', sortable: false},
                  ];
  </script>
@else
  <script type="text/javascript">
    var thData = [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'status', name: 'status'},                  
                    {data: 'action', name: 'action', sortable: false},
                  ];
  </script>
  @php $role = 3; @endphp
@endif
<script type="text/javascript">
$(document).ready(function() {
  $('#manage-users').DataTable({
    "processing": true,
    "serverSide": false,
    "pageLength": 25,
    "searching":   true,
    "bPaginate": true,
    "bLengthChange": true,
    "bInfo" : false,
    "aaSorting": [],
    "order": [[ 0, "desc" ]],
    "ajax": "{{ route('getUserListAjax', ['role'=>$role]) }}",
    "columns": thData
  });
  //$('.dataTables_processing').html('<div class="lds-ripple"><div></div><div></div></div>');
  $(document).on('click', '.delete-user', function(){
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
                      $('#manage-users').DataTable().draw();
                    }
                  }
              });
        }
    });
  });
});
</script>
@endsection