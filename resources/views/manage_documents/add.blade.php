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
                    <li class="breadcrumb-item"><a href="{{ route('manage-document') }}">Manage Documents</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
  <div class="row">
    <div class="col-md-8">
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
      <div class="card card-primary">
        <form role="form" id="add-document" action="{{ route('manage-document-save') }}" name="add-document" method="POST" accept-charset="utf-8" enctype="multipart/form-data">
          @csrf 
          <div class="card-body">
           @if(AdminHelper::checkUserIsSuperAdmin())
            <div class="form-group">
              <label for="chapter">Chapter<span class="text-danger">*</span></label>
              <select  class="form-control" id="chapter" name="chapter" onchange="chapterChanged()">
                <option value="">--- Select Chapter ---</option>
                @foreach($chapters as $chapter)
                  <option value="{{ $chapter->id }}" {{ $chapter->id==old('chapter') ? 'selected' : ''}}>{{ $chapter->chapter_name }}</option>
                @endforeach
              </select>
            </div>
             @else
            <input type="hidden" id="chapter" name="chapter" value="{{ Auth::user()->chapter_id }}">
            @endif
            <input type="hidden" id="state_hidden" name="state_hidden" value="">
            <div class="form-group">
              <label for="state">State<span class="text-danger">*</span></label>
              <select type="state" multiple data-style="col-12 bg-white px-3 py-3 shadow-sm " class="col-12 selectpicker w-100form-control " id="state" name="state[]">
                <option value="">--- Select State ---</option>
              </select>
            </div>
            <div class="form-group">
              <label for="union">Union<span class="text-danger">*</span></label>
              <select multiple data-style="bg-white px-3 py-3 shadow-sm " class="col-12 selectpicker w-100form-control " id="union" name="union[]">               
                <option value="">--- Select Union ---</option>
              </select>
            </div>
            <div class="form-group">
              <label for="document_name">Document Name<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="document_name" name="document_name" placeholder="Enter name" value="{{ old('document_name') }}">
            </div>
            <div class="form-group">
              <label for="document_type">Document Type<span class="text-danger">*</span></label>
              <select class="form-control" id="document_type" name="document_type">
                <option value="">--- Select Document Type ---</option>
                @foreach($documentTypes as $documentType)
                  <option value="{{ $documentType->id }}" {{ $documentType->id==old('document_type') ? 'selected' : ''}}>{{ $documentType->document_type_name }}</option>
                @endforeach
              </select>
            </div>
           <div class="form-group">
                <label for="doc_file">File Upload <span class="text-danger">*</span></label>
                <div class="custom-file">  
                <input type="file" name="doc_file" class="custom-file-input" id="doc_file">
                <label class="custom-file-label" for="doc_file">Choose file</label>
                </div>
                <div id="imageError"></div>
          </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('manage-document') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection

@section('style')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
function formValidation(){
  $('#add-document').validate({
    rules: {
      chapter: {
        required: true,
      },
      "state[]": {
        required: true,
      },
      "union[]": {
        required: true,
      },
      document_name: {
        required: true,
      },
      document_type: {
        required: true,
      },
      doc_file: {
        required: true,
        //accept: "doc,csv,xlsx,xls,docx"
        extension: "doc|csv|xlsx|xls|docx|doc|pdf"
        
      },  
    },
    messages: {
      chapter: {
        required: "Please select a chapter",
      },
      state: {
        required: "Please select a state",
      },
      union: {
        required: "Please select a union",
      },
      document_name: {
        required: "Please enter a document_name",
      },
      document_type: {
        required: "Please select a document type",
      },
      doc_file: {
        required: "Please upload a document",
        //accept: 'Not an file',
        extension: "Only pdf,csv,doc,xls,xlsx format are allowed"
        
        
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
}
$(document).ready(function () {
   chapterChanged();
   formValidation(); 
});
function chapterChanged() {
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
                      $('#state').html(stateList);
                      $('#state').selectpicker('destroy');
                      $('#state').selectpicker();
                    }
                }, 
                error: function (response, status, error) {
                    alert(error);
                }
            });      
    
      } else{
      }
    }
    function stateChangedNew(arrSelected) {
      var documentGetUnion = '{{ route("document-get-union-group") }}';
      var chapterId = $('#chapter').val();
      var stateId = $('#state_hidden').val();
      console.log("-----1-----");
      console.log(arrSelected);
      if(arrSelected.length>0) {
        $.ajax({
                type: 'POST',
                cache: false,
                url: documentGetUnion,
                data: { state_id : arrSelected, _token : csrf_token },
                success: function (data) {
                  console.log(data);
                    if(data)
                    {
                      console.log('-----union 2----');
                      console.log(GlobalUnions); 
                      var unionList = "";
                      for(var i=0; i< data.length;i++) {   
                         var selected = "";                         
                        if(GlobalUnions.includes(data[i].id)){
                          console.log('---yes---'); 
                          selected = " selected='selected'";
                        }
                        unionList +='<option value="'+data[i].id+'" '+selected+'>'+data[i].union_name+'</option>'; 
                      }
                      $('#union').html(unionList);
                      $('#union').selectpicker('destroy');
                      $('#union').selectpicker();       
                    }
                }, 
                error: function (response, status, error) {
                    alert(error);
                }
            });
      } else{
      }
    }
$(function () {
  $('#state').selectpicker();
  $('#union').selectpicker();
});
var GlobalUnions = [];
$(function() {
  $('#state1').on('change', function(e, clickedIndex, newValue, oldValue) {
    console.log(this.value, clickedIndex, newValue, oldValue);
    $('#state_hidden').val(e.target.value);
    stateChanged();
    $('#state').valid(); 
  });
  $('#union').on('change', function(){
    $('#union').valid(); 
  });  
  $('#state').on('change', function(e){
    $('#state').valid();
    var stateSelected = $(this).find("option:selected");    
    var arrStateSelected = [];
    stateSelected.each((idx, val) => {
      if(val.value)
        arrStateSelected.push(val.value);
    });
    console.log(arrStateSelected);
    console.log('----Union----');
    var unionSelected = $('#union').find("option:selected");  
    var arrUnionSelected = [];
    unionSelected.each((idx, val) => {
      if(val.value)
        arrUnionSelected.push(parseInt(val.value));
    });
    GlobalUnions = arrUnionSelected;
    console.log(arrUnionSelected);

    if(arrStateSelected.length>0)
      stateChangedNew(arrStateSelected);
  });
});
function stateChanged() {
      var documentGetUnion = '{{ route("document-get-union") }}';
      var chapterId = $('#chapter').val();
      var stateId = $('#state_hidden').val();
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
                      var unionList = $('#union').html();
                      for(var i=0; i< data.length;i++){                       
                        unionList +='<option value="'+data[i].id+'">'+data[i].union_name+'</option>'; 
                      }
                      $('#union').html(unionList);
                      $('#union').selectpicker('destroy');
                      $('#union').selectpicker();       
                    }
                }, 
                error: function (response, status, error) {
                    alert(error);
                }
            });
      } else{
      }
    }
</script>
@endsection