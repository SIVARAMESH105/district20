@extends('layouts.app_theme')

@section('content')
 <div class="container">
    <div class="row">
	    <div class="sidebar-filter col-md-3" id="listContent">
	        <h5>FILTERS</h5>
	        <input type="hidden" id="chapter" name="chapter" value="{{ Auth::user()->chapter_id }}">
	        <div class="sidebar-filter-list" >
	            <div class="sidebar-filter-select">
	            	<select class="custom-nice-select " name="state" id="state" onchange="stateChanged()">
                          <option value="">---State---</option>
                      </select>
	                <div class="form-control-close"></div>
	            </div>
	            <div class="sidebar-filter-select">
	            	<select class="custom-nice-select" name="union" id="union" onchange="unionChanged();">
                          <option value="">---Union---</option>
                      </select>
	            </div>
	        </div>	        
	        
	    </div>
	    <div class="col-md-9 main-content" id="sidebar-main-content">
	        <div class="row">
	            <div class="col-md-9">
	                <nav aria-label="breadcrumb">
	                    <ol class="breadcrumb">
	                        <li class="breadcrumb-item"><a href="../user">Home</a></li>
	                        <li class="breadcrumb-item active" aria-current="page">Agreements & Maps</li>
	                    </ol>
	                </nav>
	            </div>
	            <div class="col-md-3">
	                <ul class="view-select-list">
	                    <li><a href="javascript:;" id="mapButton" onclick="navButtonClicked('map')" class="map-view-select"></a></li>
	                    <li><a href="javascript:;" id="listButton" onclick="navButtonClicked('list')" class="list-view-select active"></a></li>
	                </ul>
	            </div>
	            <div class="sidebar-filter-list">
		            <div class="col-lg-12 col-12 text-center" id="mapLoader" style="display:none">
				      <div class="spinner-border text-muted"></div>
				    </div>
				    <div class="col-lg-12 col-12" id="mapContent" style="display:none">
				      <br>      
				       <div class="card">
				          <div class="row justify-content-center">  
                    <div id="chapterMap" class="col-xs-12 col-sm-8">
				              {!! App\Helpers\MapHelper::getChapterMapByChapterId(Auth::user()->chapter_id) !!}
				            </div>
				          	{!! App\Helpers\MapHelper::getStatesMapByChapterId(Auth::user()->chapter_id) !!}		        
				        </div>
				        </div>
				    </div>
		        </div>
	        </div>
	        <table id="manage-documents" class="table table-bordered display">
	            <thead>
	            <th>Document Name</th>
	            <th>Link</th>
	            </thead>
	            <tbody>                     
	            </tbody>
	        </table>
	    </div>
	</div>
</div>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('custom/datatables/css/dataTables.bootstrap4.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('custom/datatables_custom.css') }}">
<style>
.thumbnailNew{
  border: none!important;
  border-radius: 0px!important;
  box-shadow: none!important;
}
.fa-refresh:before {
    content: "\f021";
}
</style>
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('custom/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.bootstrap4.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.rowReorder.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/datatables/js/dataTables.scroller.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/datatables_custom.js') }}"></script>
<script type="text/javascript" src="{{ asset('custom/image-map-resizer-master/js/imageMapResizer.min.js') }}"></script>
<script type="text/javascript">

var thData = [
  {data: 'document_name', name: 'document_name'},
  {data: 'document_path', name: 'document_path'},                  
];
</script>
<script type="text/javascript">
    $(document).ready(function() {
      chapterChanged(); 
    });
</script>
<script type="text/javascript">    
    function chapterChanged(callback='') {
      jQuery('#manage-documents').css('width', '100%');
      jQuery('.sidebar-filter-list').css('width', '100%');  
      $('#state').html('<option value="0">---Select State---</option>');
      $('#union').html('<option value="0">---Select Union---</option>');
      applyNiceSelect();
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
                      $('#state').html('<option value="0">---Select State---</option>'+stateList);
                      applyNiceSelect();
                      if(callback){
                        console.log(callback);
                        var callbackFun = callback + '()';
                        console.log(callbackFun);
                        eval(callbackFun);
                      }                        
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
            "ajax": "{{ route('getDocumentListAjaxUserView') }}?chapter_id="+chapterId,
            "columns": thData
        });
      }
    }
    function stateChanged() {
      jQuery('#manage-documents').css('width', '100%');
      $('#union').html('<option value="0">---Select Union---</option>');
      applyNiceSelect();
      var documentGetUnion = '{{ route("document-get-union") }}';
      var chapterId = $('#chapter').val();
      var stateId = $('#state').val();
      chapterMapStateSelected(stateId);
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
                      $('#union').html('<option value="0">---Select Union---</option>'+unionList);
                      applyNiceSelect();
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
            "ajax": "{{ route('getDocumentListAjaxUserView') }}?chapter_id="+chapterId+"&state_id="+stateId,
            "columns": thData
        });
      }
    }    
    function unionChanged() {
      jQuery('#manage-documents').css('width', '100%');
      jQuery('.sidebar-filter-list').css('width', '100%');    
      var documentGetUnion = '{{ route("document-get-union") }}';
      var chapterId = $('#chapter').val();
      var stateId = $('#state').val();
      var unionId = $('#union').val();
      stateMapUnionSelected(unionId);
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
            "ajax": "{{ route('getDocumentListAjaxUserView') }}?chapter_id="+chapterId+"&state_id="+stateId+"&union_id="+unionId,
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
function chapterMapStateClicked(state_id) {
  $('#state').val(state_id);
  stateChanged();
  $('.stateMaps').css({ 'display': 'none'});
  $('#chapterMap').css({ 'display': 'none'});
  $('#state-'+state_id).css({ 'display': 'block'});
  resetimageMapResize();
}
function chapterMapStateSelected(state_id) {
  $('#state').val(state_id);
  $('.stateMaps').css({ 'display': 'none'});
  $('#chapterMap').css({ 'display': 'none'});
  $('#state-'+state_id).css({ 'display': 'block'});
  resetimageMapResize();
}

function stateMapUnionClicked(union_id) {
  $('#union').val(union_id);
  unionChanged();
}
function stateMapUnionSelected(union_id) {
  $('#union').val(union_id);
  resetimageMapResize();
}
function resetimageMapResize(){
  $('map').imageMapResize();
}
function navButtonClicked(val){
  if(val == 'map'){
    $('#listContent').css({ 'display': 'none'});
    $('#sidebar-main-content').addClass("col-md-12");
    $('#sidebar-main-content').removeClass("col-md-9");    
    $('#mapContent').css({ 'display': 'block'});
    $('#listButton').removeClass('active');
    $('#mapButton').addClass('active');
  }  else {
    $('#listContent').css({ 'display': 'block'});
    $('#sidebar-main-content').removeClass("col-md-12");
    $('#sidebar-main-content').addClass("col-md-9");    
    $('#mapContent').css({ 'display': 'none'});
    $('#mapButton').removeClass('active');
    $('#listButton').addClass('active'); 
  }
  resetimageMapResize();
}
function goToChapterMap(){
  $('.stateMaps').css({ 'display': 'none'});
  $('#mapLoader').css({ 'display': 'block'});
  $('#state').val('');
  $('#union').val('');
  chapterChanged('goToChapterMapCallback');
}
function goToChapterMapCallback(){
  $('#chapterMap').css({ 'display': 'block'});
  $('#mapLoader').css({ 'display': 'none'});
  resetimageMapResize();
}
function resetStateMap(){
  $('#union').val('');
  //alert($('#state').val());
  stateChanged();
}
resetimageMapResize();
function applyNiceSelect(){
  $('.custom-nice-select').niceSelect('update');
  }
</script>
@endsection