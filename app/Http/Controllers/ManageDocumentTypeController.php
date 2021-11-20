<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentType;

/**
 * Class ManageDocumentTypeController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Models\DocumentType
 */
class ManageDocumentTypeController extends Controller
{    
    /**
     * This function is used to get document types index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function index(Request $request)
    {
        $data['title'] = "Manage Document Types";
        return view('manage_document_type.index', $data);
    }
    /**
     * This function is used to get document types list ajax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function getDocumentTypeListAjax(Request $request)
    {
        return datatables()->of((new DocumentType)->getAllDocumentTypeAjax())
                            ->addColumn('action', function($dt){
                                $action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="'.route('manage-document-type-edit', $dt->id) .'"><i class="fas fa-pencil-alt"></i></a>';
                                    $action .=' <a class="btn btn-danger btn-xs delete-document-type" href="javascript:;" data-id="'.$dt->id.'" data-url="'.route('manage-document-type-delete', $dt->id) .'"><i class="fas fa-trash"></i></a></div>';
                                return $action;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }
    /**
     * This function is used to get add document types page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function add(Request $request)
    {
        $data['title'] = "Manage Document Types - Add";
        return view('manage_document_type.add', $data);
    }
    /**
     * This function is used to save document types
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function save(Request $request)
    {
        if (!$request->name) {
            return back()->withInput()->withErrors(['name'=>'Please enter document type name.']);
        }
        $info['document_type_name'] = $request->name;
        if((new DocumentType)->createDocumentType($info)) {
            $request->session()->flash('success', "New Document Type Created Successfully.");
            return redirect(route('manage-document-type'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-document-type'))->withInput();
        }
    }
    /**
     * This function is used to get edit manage document type page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        if(!$id){
            $request->session()->flash('error', "Something Went Wrong!.");
            return redirect(route('manage-document-type'))->withInput();
        }
        $data['info'] = DocumentType::find($id);
        $data['title'] = "Manage Document Types - Edit";
        return view('manage_document_type.edit', $data);
    }
    
    /**
     * This function is used to update document type
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function update(Request $request)
    {
        if (!$request->name) {
            return back()->withInput()->withErrors(['name'=>'Please enter document type name.']);
        }
        $info['document_type_name'] = $request->name;   
        $info['id'] = $request->id;   
        if((new DocumentType)->updateDocumentType($info)) {
            $request->session()->flash('success', "Document Type Updated Successfully.");
            return redirect(route('manage-document-type'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-document-type'))->withInput();
        }
    }
    /**
     * This function is used to delete document type
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if($id)
            return (new DocumentType)->deleteDocumentType($id);
        else
            return false;
    }
}