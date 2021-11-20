@extends('layouts.app_theme')
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
        <br>
        @if(count($errors) > 0)
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
      </div>
      <div class="col-lg-12 col-12"> 
        <button class="btn btn-primary btn-md" id="listButton" onclick="navButtonClicked('list')">List</button>
        <button class="btn btn-secondary btn-md" id="mapButton" onclick="navButtonClicked('map')">Map</button>
      </div>    
    <div class="col-lg-12 col-12" id="listContent">
      <br>    
       <form method="get" class="" role="form">
          <div class="row">
            <input type="hidden" id="chapter" name="chapter" value="{{ Auth::user()->chapter_id }}">
            
              <div class="col-sm-12">
                  <div class="form-group">
                      <select class="form-control " name="state" id="state" onchange="stateChanged()">
                          <option value="">---State---</option>
                      </select>
                  </div>
              </div>
              <div class="col-sm-12">
                  <div class="form-group">
                      <select class="form-control" name="union" id="union" onchange="unionChanged();">
                          <option value="">---Union---</option>
                      </select>
                  </div>
              </div>
          </div>
      </form> 
    </div>
    <div class="col-lg-12 col-12 text-center" id="mapLoader" style="display:none">
      <div class="spinner-border text-muted"></div>
    </div>
    <div class="col-lg-12 col-12" id="mapContent" style="display:none">
      <br>      
       <div class="card">
          <div class="row">
            <div id="chapterMap">
              {!! App\Helpers\MapHelper::getChapterMapByChapterId(Auth::user()->chapter_id) !!}
            </div>
          {!! App\Helpers\MapHelper::getStatesMapByChapterId(Auth::user()->chapter_id) !!}
        <!-- Map Example code 
          <div class="col-xs-12 col-sm-4">
          <img class="img-thumbnail" src="{{ asset('/images/map.png') }}" title="Image Map" usemap="#map1" >
          <map name="map1">
              <area onclick="alert(10);" alt="MVL" title="MVL" href="#MVL1" shape="poly" coords="409, 89, 402, 163, 394, 247, 424, 255, 426, 278, 526, 282, 537, 309, 531, 346, 534, 352, 601, 349, 604, 358, 613, 347, 617, 335, 600, 313, 596, 309, 601, 303, 591, 299, 582, 278, 579, 267, 582, 248, 595, 237, 591, 222, 625, 221, 629, 220, 633, 169, 627, 175, 625, 158, 596, 145, 578, 135, 568, 137, 584, 119, 595, 113, 570, 106, 547, 100, 525, 90, 469, 92, 416, 88" />
              <area onclick="alert(11);" alt="WL" title="WL" href="#WL1" shape="poly" coords="108, 177, 153, 190, 207, 204, 219, 159, 231, 133, 226, 120, 240, 65, 320, 79, 408, 92, 401, 167, 393, 243, 394, 252, 423, 255, 422, 291, 421, 315, 421, 336, 341, 333, 236, 313, 225, 332, 215, 329, 218, 357, 218, 368, 208, 384, 206, 397, 165, 390, 154, 364, 124, 338, 109, 295, 115, 286, 106, 270, 103, 258, 101, 237, 97, 221, 97, 203" />
              <area onclick="alert(12);" alt="SWL" title="SWL" href="#SWL1" shape="poly" coords="309, 323, 233, 313, 226, 333, 218, 330, 218, 358, 219, 370, 208, 388, 207, 400, 216, 408, 228, 418, 248, 430, 268, 436, 299, 442, 308, 436, 327, 435, 344, 445, 351, 456, 364, 470, 367, 485, 384, 499, 407, 480, 433, 501, 453, 535, 463, 562, 491, 567, 487, 529, 505, 514, 533, 492, 551, 477, 547, 452, 542, 424, 541, 405, 532, 367, 531, 340, 534, 310, 525, 284, 503, 277, 442, 278, 421, 278, 420, 318, 419, 338, 391, 336, 343, 331" />
              <area onclick="alert(13);" alt="ALB" title="ALB" href="#ALB1" shape="poly" coords="618, 346, 618, 346, 637, 343, 756, 328, 824, 314, 814, 304, 809, 281, 807, 264, 816, 282, 823, 301, 829, 274, 820, 273, 813, 251, 741, 262, 739, 254, 736, 221, 715, 232, 704, 234, 694, 228, 703, 207, 700, 186, 694, 184, 689, 189, 683, 191, 689, 175, 685, 159, 668, 151, 659, 157, 655, 168, 647, 180, 649, 204, 650, 230, 639, 236, 632, 233, 626, 222, 636, 167, 632, 160, 640, 152, 653, 146, 666, 146, 675, 145, 663, 135, 658, 128, 640, 135, 632, 138, 620, 132, 612, 133, 618, 121, 618, 120, 593, 137, 593, 141, 616, 151, 625, 156, 635, 165, 628, 218, 609, 224, 593, 224, 590, 223, 596, 236, 584, 245, 579, 267, 587, 291, 600, 301, 597, 312, 614, 329, 618, 347, 634, 343, 637, 343, 757, 328" />
              <area onclick="alert(14);" alt="NEL" title="NEL" href="#NEL1" shape="poly" coords="735, 219, 740, 264, 813, 249, 821, 273, 831, 274, 819, 257, 832, 262, 837, 242, 835, 228, 863, 209, 867, 203, 888, 189, 882, 180, 879, 186, 871, 180, 868, 166, 878, 144, 895, 128, 908, 117, 904, 107, 894, 100, 883, 69, 869, 74, 866, 73, 860, 102, 856, 119, 847, 127, 817, 138, 800, 142, 787, 160, 790, 173, 780, 182, 756, 183, 752, 189, 755, 200" />
              <area onclick="alert(15);" alt="NWL" title="NWL" href="#NWL1" shape="poly" coords="106, 174, 124, 119, 138, 63, 140, 46, 162, 63, 162, 76, 170, 45, 216, 59, 238, 63, 224, 120, 234, 131, 216, 152, 219, 162, 209, 194, 207, 204, 164, 192, 127, 181" />
              <area onclick="alert(16);" alt="NWL1" title="NWL1" href="#NWL111" shape="poly" coords="178, 506, 178, 506, 364, 583, 413, 636, 384, 660, 318, 592, 272, 584, 241, 639, 159, 662, 61, 672, 62, 652, 119, 632, 89, 582, 96, 544, 126, 520, 169, 502, 177, 504" />
              <area onclick="alert(17);" alt="SEL" title="SEL" href="#SEL11" shape="poly" coords="551, 487, 580, 490, 586, 486, 600, 499, 619, 495, 629, 495, 624, 482, 641, 477, 670, 472, 695, 479, 715, 473, 741, 485, 748, 520, 763, 542, 782, 558, 795, 559, 791, 537, 792, 520, 775, 491, 754, 445, 768, 412, 786, 389, 817, 354, 829, 347, 824, 319, 822, 312, 720, 335, 650, 339, 599, 351, 532, 350, 534, 395, 540, 414, 549, 481" />
              <area onclick="alert(18);" alt="SEL1" title="SEL1" href="#SEL111" shape="poly" coords="555, 615, 555, 615, 713, 553, 894, 655, 889, 709, 681, 709, 555, 655, 544, 629, 556, 614" />
          </map>
        </div>    -->
        </div>
        </div>
    </div>
    <div class="col-lg-12 col-12">
      <div class="table-responsive">
        <table id="manage-documents" class="table table-bordered">
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
      $('#union').html('<option value="">---Select Union---</option>');
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
            "ajax": "{{ route('getDocumentListAjaxUserView') }}?chapter_id="+chapterId+"&state_id="+stateId,
            "columns": thData
        });
      }
    }    
    function unionChanged() {
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
    $('#mapContent').css({ 'display': 'block'});
    $('#mapButton').removeClass('btn-secondary');
    $('#mapButton').addClass('btn-primary');
    $('#listButton').addClass('btn-secondary');    
  }  else {
    $('#listContent').css({ 'display': 'block'});
    $('#mapContent').css({ 'display': 'none'});
    $('#listButton').removeClass('btn-secondary');
    $('#listButton').addClass('btn-primary');
    $('#mapButton').addClass('btn-secondary');   
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
</script>
@endsection