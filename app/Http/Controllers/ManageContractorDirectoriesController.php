<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DirectoriesCreateRequest;
use App\Http\Requests\DirectoriesUpdateRequest;
use App\Models\Chapter;
use App\Models\Directories;
use App\Helpers\CommonHelper;
use Auth;

/**
 * Class ManageContractorDirectoriesController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Http\Requests\DirectoriesCreateRequest
 * @package App\Http\Requests\DirectoriesUpdateRequest
 * @package App\Models\Chapter
 * @package App\Models\Directories
 * @package Auth
 */
class ManageContractorDirectoriesController extends Controller
{
    /**
     * This function is used to get manage directories index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:syamala
     */
    public function index(Request $request)
    {
    	$data['directories'] = Directories::all();
        $data['title'] = "Manage Contractor Directory";
        return view('manage_contractor_directories.index', $data);
    }

    /**
     * This function is used to get directories list ajax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:syamala
     */
    public function getdirectoriesListAjax(Request $request)
    {
        return datatables()->of((new Directories)->getAllDirectoriesAjax())
                        ->addColumn('action', function($directories){
                            $action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="'.route('manage-contractor-directories-edit', $directories->id) .'"><i class="fas fa-pencil-alt"></i></a>';
                            $action .=' <a class="btn btn-danger btn-xs delete-directory" href="javascript:;" data-id="'.$directories->id.'" data-url="'.route('manage-contractor-directories-delete', $directories->id) .'"><i class="fas fa-trash"></i></a></div>';
                            return $action;
                        })                       
                        ->rawColumns(['action'])
                        ->make(true);
    }

    /**
     * This function is used to get add manage directories page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:syamala
     */
    public function add(Request $request)
    {   
        $data['title'] = "Manage Contractor Directory - Add";        
        $data['chapters'] = Chapter::all();
        return view('manage_contractor_directories.add', $data);
    }

    /**
     * This function is used to save manage directories
     *
     * @param DirectoriesCreateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:syamala
     */
    // public function save(DirectoriesCreateRequest $request)
    public function save(Request $request)
    {
        $info['name'] = $request->name;
        $info['email'] = $request->email;     
        $info['chapter'] = $request->chapter;
        $info['position'] = $request->position;
        $info['address'] = $request->address;
        $info['state'] = $request->state; 
        $info['zipcode'] = $request->zipcode; 
        $info['phone'] = $request->phone; 
        $info['fax'] = $request->fax; 
        $info['website'] = $request->website;
        $info['city'] = $request->city;
        $info['contact'] = $request->contact;

        $info['profile_pic'] = '';
        if($request->profile_pic){
            $directory = public_path().'/directories';
            if (!is_dir($directory)) {
                mkdir($directory);
                chmod($directory, 0777);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_pic')->getClientOriginalName());
            $request->file('profile_pic')->move($directory, $imageName);
            $info['profile_pic'] = 'directories/'.$imageName;
        }
        if((new Directories)->createDirectory($info)) {     
            $request->session()->flash('success', "New Contractor Directory Created Successfully.");
            return redirect(route('manage-contractor-directories'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-contractor-directories'))->withInput();
        }
    }

    /**
     * This function is used to get edit manage directory page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:syamala
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        if(!$id){
            $request->session()->flash('error', "Something Went Wrong!.");
            return redirect(route('manage-contractor-directories'))->withInput();
        }
        $data['chapters'] = Chapter::all();
        $data['info'] = $info = Directories::find($id);
        if(!$info) {
            $request->session()->flash('error', "Unable to find contractor directory.");
            return redirect(route('manage-contractor-directories'))->withInput();
        }        
        $data['title'] = "Manage Contractor Directory - Edit";
        return view('manage_contractor_directories.edit', $data);
    }

    /**
     * This function is used to update manage directories
     *
     * @param DirectoriesUpdateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:syamala
     */
    public function update(DirectoriesUpdateRequest $request)
    {
        $info['name'] = $request->name;
        $info['email'] = $request->email;     
        $info['chapter'] = $request->chapter;
        $info['position'] = $request->position;
        $info['address'] = $request->address;
        $info['state'] = $request->state; 
        $info['zipcode'] = $request->zipcode; 
        $info['phone'] = $request->phone; 
        $info['fax'] = $request->fax; 
        $info['website'] = $request->website;   
        $info['id'] = $request->id;
        $info['city'] = $request->city;
        $info['contact'] = $request->contact;
        
        $info['profile_pic'] = '';
        if($request->profile_pic) {
            $directory = public_path().'/directories';
            if (!is_dir($directory)) {
                mkdir($directory);
                chmod($directory, 0777);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_pic')->getClientOriginalName());
            $request->file('profile_pic')->move($directory, $imageName);
            $info['profile_pic'] = 'directories/'.$imageName;
        }

        if((new Directories)->updateDirectory($info)) {
            $request->session()->flash('success', "Contractor Directory Updated Successfully.");
            return redirect(route('manage-contractor-directories'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-contractor-directories'))->withInput();
        }
    }

    /**
     * This function is used to delete manage directory
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:syamala
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if($id)
            return (new Directories)->deleteDirectory($id);
        else
            return false;
    }

    /**
     * This function is used to display the view directories for the user
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:sivaramesh
     */
    public function view(Request $request)
    {
        $data['directories'] = Directories::all();
        $data['title'] = "Contractor Directory";
        return view('manage_contractor_directories.view', $data);
    }
    /**
     * This function is used to import directories
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function import(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = CommonHelper::importContractorElseChapterDirectory($request, 1);
            if($data) {
                 $request->session()->flash('success', "Contractor Directory Imported Successfully.");
                return redirect(route('manage-contractor-directories'));
            } else {
                $request->session()->flash('error', "Unable to read file (or) invalid input");
                return redirect(route('manage-contractor-directories'));
            }
        }
        $data['title'] = "Manage Contractor Directory - Import";
        return view('manage_contractor_directories.import', $data);        
    }
}