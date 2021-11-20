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
          @if(AdminHelper::checkUserIsChapterAdmin())
              <div class="offset-8 col-sm-4 text-right" style="padding: 10px;"><a href="{{ route('manage-document-add') }}" class="btn btn-info btn-sm">{{ $addText }}</a></div>
              <div class="offset-8 col-sm-4 text-right" style="padding: 10px;">
                <a href="{{ route('manage-document-import') }}" class="btn btn-info btn-sm">Import Documents</a>
            </div>
          @endif 
           <form method="get" class="" role="form">
              <div class="row">
                @if(AdminHelper::checkUserIsSuperAdmin())
                  <div class="{{$columnClass}}">
                      <div class="form-group">
                          <select type="chapter" class="form-control" id="chapter" name="chapter"  onchange="chapterChanged()">
                              <option value="all">--- Select Chapter ---</option>
                              @foreach($chapters as $chapter)
                              <option value="{{ $chapter->id }}">{{ $chapter->chapter_name }}</option>
                              @endforeach
                             
                          </select>
                      </div>
                  </div>
                @else
                <input type="hidden" id="chapter" name="chapter" value="{{ Auth::user()->chapter_id }}">
                @endif
                  <div class="{{$columnClass}}">
                      <div class="form-group">
                          <select class="form-control " name="state" id="state" onchange="stateChanged()">
                              <option value="">---State---</option>
                          </select>
                      </div>
                  </div>
                  <div class="{{$columnClass}}">
                      <div class="form-group">
                          <select class="form-control" name="union" id="union" onchange="unionChanged();">
                              <option value="">---Union---</option>
                          </select>
                      </div>
                  </div>
              </div>
          </form>
          <div class="table-responsive">
              <table id="manage-documents" class="table table-bordered">
                  <thead>
                  <th>Document Name</th>
                  <th>Link</th>
                  <th>Action</th>
                  </thead>
                  <tbody>                     
                  </tbody>
              </table>
          </div>
      </div> <!-- col-lg-12 close-->
  </div><!-- row close-->
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
    var thData = [
                  {data: 'document_name', name: 'document_name'},
                  {data: 'document_path', name: 'document_path'},                  
                  {data: 'action', name: 'action', sortable: false},
                ];
</script>
@if(AdminHelper::checkUserIsSuperAdmin())
  <script type="text/javascript">
    $(document).ready(function() {
      var chapterId = $('#chapter').val();
      $('#manage-documents').DataTable({
        "processing": false,
        "serverSide": true,
        "pageLength": 25,
        "searching":   true,
        "bPaginate": true,
        "bLengthChange": true,
        "bInfo" : false,
        "aaSorting": [],
        "order": [[ 0, "desc" ]],
        "ajax": "{{ route('getDocumentListAjax') }}?chapter_id="+chapterId,
        "columns": thData
      });
      $('.dataTables_processing').html('<div class="lds-ripple"><div></div><div></div></div>');     
    });
  </script>
@else
<script type="text/javascript">
    $(document).ready(function() {
      chapterChanged(); 
    });
</script>
@endif
<script type="text/javascript">    
    function chapterChanged() {
      $('#state').html('<option value="">---Select State---</option>');
      $('#union').html('<option value="">---Select Union---</option>');
      var documentGetState = '{{ route("document-get-state") }}';
      var chapterId = $('#chapter').val();    
      if(chapterId) {
        $.ajax({
                type: 'POST',
                cache: false,
                url: documentGetState,
                data: { chapter_id : chapterId, _token : csrf_token },
                success: function (data) {
                  console.log(data);
                    if(data)
                    {
                      var stateList="";
                      for(var i=0; i< data.length;i++){
                       
                        stateList +='<option value="'+data[i].id+'">'+data[i].state_name+'</option>'; 
                      }
                      $('#state').html('<option value="">---Select State---</option>'+stateList);
                              
                    }
                }, 
                error: function (response, status, error) {
                    alert(error);
                }
            });
         $('#manage-documents').DataTable().destroy();
         $('#manage-documents').DataTable({
            "processing": false,
            "serverSide": true,
            "pageLength": 25,
            "searching":   true,
            "bPaginate": true,
            "bLengthChange": true,
            "bInfo" : false,
            "aaSorting": [],
            "order": [[ 0, "desc" ]],
            "ajax": "{{ route('getDocumentListAjax') }}?chapter_id="+chapterId,
            "columns": thData
        });
      }
    }
    function stateChanged() {
      $('#union').html('<option value="">---Select Union---</option>');
      var documentGetUnion = '{{ route("document-get-union") }}';
      var chapterId = $('#chapter').val();
      var stateId = $('#state').val();
      if(stateId) {
        $.ajax({
                type: 'POST',
                cache: false,
                url: documentGetUnion,
                data: { state_id : stateId, _token : csrf_token },
                success: function (data) {
                  console.log(data);
                    if(data)
                    {
                      var unionList="";
                      for(var i=0; i< data.length;i++){
                       
                        unionList +='<option value="'+data[i].id+'">'+data[i].union_name+'</option>'; 
                      }
                      $('#union').html('<option value="">---Select Union---</option>'+unionList);
                              
                    }
                }, 
                error: function (response, status, error) {
                    alert(error);
                }
            });
        $('#manage-documents').DataTable().destroy();   
         $('#manage-documents').DataTable({
            "processing": false,
            "serverSide": true,
            "pageLength": 25,
            "searching":   true,
            "bPaginate": true,
            "bLengthChange": true,
            "bInfo" : false,
            "aaSorting": [],
            "order": [[ 0, "desc" ]],
            "ajax": "{{ route('getDocumentListAjax') }}?chapter_id="+chapterId+"&state_id="+stateId,
            "columns": thData
        });
      }
    }    
    function unionChanged() {
      var documentGetUnion = '{{ route("document-get-union") }}';
      var chapterId = $('#chapter').val();
      var stateId = $('#state').val();
      var unionId = $('#union').val();
      if(stateId) {
        $('#manage-documents').DataTable().destroy();   
         $('#manage-documents').DataTable({
            "processing": false,
            "serverSide": true,
            "pageLength": 25,
            "searching":   true,
            "bPaginate": true,
            "bLengthChange": true,
            "bInfo" : false,
            "aaSorting": [],
            "order": [[ 0, "desc" ]],
            "ajax": "{{ route('getDocumentListAjax') }}?chapter_id="+chapterId+"&state_id="+stateId+"&union_id="+unionId,
            "columns": thData
        });
      }
    }
</script>
<script>
  $(document).on('click', '.delete-document', function(){
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
                        text: "Document Deleted Successfully.",
                        type: "success",
                        confirmButtonColor: "#22D69D"
                    });
                      $('#manage-documents').DataTable().draw();
                    }
                  }
              });
        }
    });
  });
</script>
@endsection