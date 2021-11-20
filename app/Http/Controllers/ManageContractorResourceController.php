<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ContractorResource;
use App\Models\Chapter;

/**
 * Class ManageContractorResourceController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package Illuminate\Support\Facades\Validator
 * @package App\Models\ContractorResource
 * @package App\Models\Chapter
 */
class ManageContractorResourceController extends Controller
{    
    /**
     * This function is used to get manage contractor resource index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function index()
    {
        $data['title'] = "Manage Contractor Resources";
        return view('manage_contractor_resources.index', $data);
    }
    
    /**
     * This function is used to get contractor resources list
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function getContractorResourcesListAjax(Request $request)
    {
        return datatables()->of((new ContractorResource)->getAllContractorResourceAjax())
                        ->editColumn('url', function($resource){     
                               return '<h6><a target="_blank" href="'. $resource->url .'">Resource Link</a></h6>';
                            })
                        ->addColumn('action', function($resource){                            
                            $action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="'.route('manage-contractor-resources-edit', $resource->id) .'"><i class="fas fa-pencil-alt"></i></a>'; 
                            $action .=' <a class="btn btn-danger btn-xs delete-resource" href="javascript:;" data-id="'.$resource->id.'" data-url="'.route('manage-contractor-resources-delete', $resource->id) .'"><i class="fas fa-trash"></i></a></div>';                            
                            return $action;
                        })
                        ->rawColumns(['url','action'])
                        ->make(true);
    }

    /**
     * This function is used to get add contractor resource add page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function add(Request $request)
    {         
        $data['chapters'] = Chapter::all(); 
        $data['title'] = "Manage Contractor Resources - Add";
        return view('manage_contractor_resources.add', $data);
    }

    /**
     * This function is used to save contractor resources
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function save(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'title' => 'required',
            'url' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect(route('manage-contractor-resources-add'))
                        ->withErrors($validator)
                        ->withInput();
        }
        $info['title'] = $request->title;
        $info['url'] = $request->url;
        $info['chapter'] = $request->chapter;
        $info['description'] = $request->description;
        if((new ContractorResource)->createContractorResource($info)) {
            $request->session()->flash('success', "New Contractor Resources Created Successfully.");
            return redirect(route('manage-contractor-resources'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-contractor-resources'))->withInput();
        }    
    }
    
    /**
     * This function is used to get contractor resources edit page
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
            return redirect(route('manage-contractor-resources'))->withInput();
        }
        $data['chapters'] = Chapter::all();
        $data['info'] = $info = ContractorResource::find($id);
        if(!$info) {
            $request->session()->flash('error', "Unable to find contractor resource.");
            return redirect(route('manage-contractor-resources'))->withInput();
        }
        $data['title'] = "Manage Contractor Resources - Edit";
        return view('manage_contractor_resources.edit', $data);
    }

    /**
     * This function is used to update contractor resources 
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function update(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'title' => 'required',
            'id' => 'required',
            'url' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect(route('manage-contractor-resources-edit', $request->id))
                        ->withErrors($validator)
                        ->withInput();
        }

        $info['title'] = $request->title;
        $info['url'] = $request->url;
        $info['chapter'] = $request->chapter;
        $info['description'] = $request->description;
        $id = $info['id'] =$request->id;
        if(!$id){     
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-contractor-resources'))->withInput();
        }
        if((new ContractorResource)->updateContractorResource($info)) {
            $request->session()->flash('success', "Contractor Resource Updated Successfully.");
            return redirect(route('manage-contractor-resources'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-contractor-resources'))->withInput();
        }
    }

    /**
     * This function is used to delete contractor resource
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if($id)
            return (new ContractorResource)->deleteContractorResource($id);
        else
            return false;
    }
}