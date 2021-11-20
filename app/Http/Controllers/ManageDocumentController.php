<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\UrlGenerator;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Documents;
use App\Models\DocumentLocation;
use App\Models\UsersRole;
use App\Models\States;
use App\Models\Union;  
use App\Models\Representative;
use App\Models\Chapter;
use App\Models\DocumentType;
use App\Helpers\CommonHelper;
use Auth;
use AdminHelper;

/**
 * Class ManageUserController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package Illuminate\Support\Facades\Validator
 * @package Illuminate\Routing\UrlGenerator
 * @package App\Http\Requests\UserCreateRequest
 * @package App\Http\Requests\UserUpdateRequest
 * @package App\Models\Documents
 * @package App\Models\UsersRole
 * @package App\Models\States
 * @package App\Models\Union
 * @package App\Models\Representative
 * @package App\Models\Chapter
 * @package App\Models\DocumentType
 * @package App\Helpers\CommonHelper
 * @package Auth
 * @package AdminHelper
 */
class ManageDocumentController extends Controller
{

    /**
     * This function is used to get manage document index page
     *
     * @param Request $request
     * @return view
     * @author Techaffinity:sivaramesh
     */
    public function index(Request $request)
    {
        $data['role'] = $request->r;
        $data['roles'] = UsersRole::all();
        $data['title'] = "Manage Documents";
        $data['chapters'] = Chapter::all();
        $data['addText'] = "Add Document"; 
        if(AdminHelper::checkUserIsSuperAdmin()){
            $data['columnClass'] = "col-sm-4";
        }else{
            $data['columnClass'] = "col-sm-6";
        }
        return view('manage_documents.index', $data);
    }
    /**
     * This function is used to get document list ajax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:sivaramesh
     */
    public function getDocumentListAjax(Request $request)
    {
        $chapter_id = $request->chapter_id;
        $state_id = $request->state_id;
        $union_id = $request->union_id;
        //return (new Documents)->getAllDocumentsAjax($chapter_id,$state_id,$union_id);
        return datatables()->of((new Documents)->getAllDocumentsAjax($chapter_id,$state_id,$union_id))
                        ->addColumn('action', function($document){
                            if(AdminHelper::checkUserIsChapterAdmin()){  
                                $action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="'.route('manage-document-edit', $document->id) .'"><i class="fas fa-pencil-alt"></i></a>'; 
                                $action .=' <a class="btn btn-danger btn-xs delete-document" href="javascript:;" data-id="'.$document->id.'" data-url="'.route('manage-document-delete', $document->id) .'"><i class="fas fa-trash"></i></a></div>';
                            }else{
                                $action = '<div class="action-btn"><a class="btn btn-dark btn-xs" title="view" href="'.route('manage-document-edit', $document->id) .'"><i class="fas fa-eye"></i></a>'; 
                            }
                            return $action;
                        })
                        ->editColumn('document_path', function($document){
                            if(AdminHelper::checkUserIsChapterAdmin()){ 
                                return '<a href="'.asset($document->document_full_path).'" target="_blank"><i class="fa fa-file"></i></a>';
                            }else{
                                 return '<a href="'.asset($document->document_full_path).'" target="_blank"><i class="fa fa-file"></i></a>';
                            }
                        })
                         ->rawColumns(['action', 'document_path'])
                        ->make(true);
    }
    /**
     * This function is used to get state list
     *
     * @return view
     * @author Techaffinity:sivaramesh  
     */
    public function getDocumentState(Request $request) {
        $this->state = new States();
        if($request->chapter_id){  
            return $this->state->getDocumentState($request->chapter_id);
        }        
    }
    /**
     * This function is used to get add document page
     *
     * @param Request $request
     * @return view
     * @author Techaffinity:sivaramesh
     */
    public function add(Request $request)
    {
        $data['roles'] = UsersRole::all()->sortByDesc("id");
        $data['chapters'] = Chapter::all();
        $data['documentTypes'] = DocumentType::all();
        $data['title'] = "Manage Documents - Add";
        $data['brVal'] = "Manage Documents";
        return view('manage_documents.add', $data);
    }
    /**
     * This function is used to save manage document
     *
     * @param DirectoriesCreateRequest $request
     * @return view
     * @author Techaffinity:sivaramesh
     */
    public function save(Request $request)
    {
        //return $request->all();
        $validator = Validator::make($request->all(), ['doc_file' => 'required']);
        if ($validator->fails()) {
            $data['roles'] = UsersRole::all()->sortByDesc("id");
            $data['chapters'] = Chapter::all();
            $data['documentTypes'] = DocumentType::all();
            $data['title'] = "Manage Documents - Add";
            $data['brVal'] = "Manage Documents";
            return view('manage_documents.add',$data)->with('errors', $validator->errors());
        }
        $documentInfo['document_name'] = $info['document_name'] = $request->document_name;
        $documentInfo['document_type'] = $info['document_type'] = $request->document_type;
        $docPath = 'documents/'; // upload path
        $document_name = str_replace(' ', '-', date('YmdHis') . "." .$request->file('doc_file')->getClientOriginalName());
        $documentInfo['document_path'] = $info['document_path'] =  $docPath .$document_name;
        $request->file('doc_file')->move($docPath, $document_name);
        $info['union_id'] = $request->union;
        $created = false;
        $document_id = (new Documents)->createDocument($documentInfo);
        foreach($info['union_id'] as $union_id){
            $data['document_id'] = $document_id;
            $data['chapter_id'] = $request->chapter;
            $data['union_id'] = $union_id;
            $data['state_id'] = Union::where('id', $union_id)->first()->state_id; // set state id
            $created = DocumentLocation::create($data);
        }
        if($created) {
            $request->session()->flash('success', "New Document Created Successfully.");
            return redirect(route('manage-document'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-document'))->withInput();
        }
    }
    public function edit(Request $request)
    {
        $id = $request->id;
        if(!$id){
            $request->session()->flash('error', "Something Went Wrong!.");
            return redirect(route('manage-documents'))->withInput();
        }
        $data['documentTypes'] = DocumentType::all();
        $data['chapters'] = Chapter::all();
        $data['info'] = $info = Documents::find($id);
        $data['chapter_id'] = DocumentLocation::where('document_id', $id)->first()->chapter_id;
        $data['documentStates'] = DocumentLocation::where('document_id', $id)->pluck('state_id')->toArray();
        $data['documentUnions'] = DocumentLocation::where('document_id', $id)->pluck('union_id')->toArray();
        if($data['chapter_id']){  
            $data['states'] = (new States)->getDocumentState($data['chapter_id']);           
        }
        if($data['chapter_id']){  
            $data['unions'] = (new Union)->getDocumentUnionGroup($data['documentStates']);            
        }       
        if(!$info) {
            $request->session()->flash('error', "Unable to find directory.");
            return redirect(route('manage-documents'))->withInput();
        }
        $data['title'] = "Manage Document - Edit";
        return view('manage_documents.edit', $data);
    }
    
    /**
     * This function is used to update the  manage Document
     *
     * @param DocumentUpdateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:sivaramesh
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), ['document_name' => 'required']);
        if ($validator->fails()) {
            return $this->edit($request);
        }
        $documentInfo['document_name'] = $info['document_name'] = $request->document_name;
        $documentInfo['document_type'] = $info['document_type'] = $request->document_type;
        $documentInfo['document_path'] = $info['document_path'] = $request->doc_path;
         $documentInfo['id'] = $info['id'] = $request->id;
        if ($files = $request->file('doc_file')) {
                $docPath = 'documents/'; // upload path
                $document_name = str_replace(' ', '-', date('YmdHis') . "." .$files->getClientOriginalName());
                $documentInfo['document_path'] =  $info['document_path'] =  $docPath .$document_name;
                $files->move($docPath, $document_name);
        }
        $created = false;
        $updated = (new Documents)->updateDocument($documentInfo);
        DocumentLocation::where('document_id', $request->id)->delete();
        foreach($request->union as $union_id){
            $data['document_id'] = $request->id;
            $data['chapter_id'] = $request->chapter;
            $data['union_id'] = $union_id;
            $data['state_id'] = Union::where('id', $union_id)->first()->state_id; // set state id
            $created = DocumentLocation::create($data);
        }
        if($created) {
            $request->session()->flash('success', "Document Updated Successfully.");
            return redirect(route('manage-document'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-document'))->withInput();
        }
    }
    /**
     * This function is used to get union list
     *
     * @return view
     * @author Techaffinity:sivaramesh
     */
    public function getDocumentUnion(Request $request) {  
        if($request->state_id){  
            return (new Union)->getDocumentUnion($request->state_id);
        }        
    }
    /**
     * This function is used to get document union group
     *
     * @return view
     * @author Techaffinity:sivaramesh
     */
    public function getDocumentUnionGroup(Request $request) {
        if($request->state_id){  
            return (new Union)->getDocumentUnionGroup($request->state_id);
        }        
    }
    
    /**
     * This function is used to delete manage document
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:sivaramesh
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if($id){
            DocumentLocation::where('document_id', $id)->delete();
            return (new Documents)->deleteDocument($id);
        }            
        else
            return false;
    }
    /**
     * This function is used to display the view document index page
     *
     * @param Request $request
     * @return view
     * @author Techaffinity:sivaramesh
     */
    public function view(Request $request)
    {
        $data['role'] = $request->r;
        $data['roles'] = UsersRole::all();
        $data['title'] = "View Documents";
        $data['chapters'] = Chapter::all();
        $data['columnClass'] = "col-sm-6";
        return view('manage_documents.view', $data);
    } 
    /**
     * This function is used to get edit manage document page
     *
     * @param Request $request
     * @return view
     * @author Techaffinity:sivaramesh
     */
    public function edit_bkp(Request $request)
    {
        $id = $request->id;
        if(!$id){
            $request->session()->flash('error', "Something Went Wrong!.");
            return redirect(route('manage-documents'))->withInput();
        }
        $data['documentTypes'] = DocumentType::all();
        $data['chapters'] = Chapter::all();
        $data['info'] = $info = Documents::find($id);
        if($data['info']->chapter_id){  
           $data['states'] = (new States)->getDocumentState($data['info']->chapter_id);
        }
        if($data['info']->state_id){  
            $data['unions'] = (new Union)->getDocumentUnion($data['info']->state_id);
        }       
        if(!$info) {
            $request->session()->flash('error', "Unable to find directory.");
            return redirect(route('manage-documents'))->withInput();
        }  
        $data['chapters'] = Chapter::all();     
        $data['title'] = "Manage Document - Edit";
        return view('manage_documents.edit', $data);
    }  
    /**
     * This function is used to import documents
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function import(Request $request)
    {
        if ($request->isMethod('post')) { 
            if(CommonHelper::importDocuments($request, $request->chapter)) {
                 $request->session()->flash('success', "Documents Imported Successfully.");                
            } else {
                $request->session()->flash('error', "Unable to read file (or) invalid input");                
            }
            return redirect(route('manage-document'));
        }
        $data['chapters'] = Chapter::all();
        $data['title'] = "Manage Documents - Import";
        return view('manage_documents.import', $data);        
    }
}