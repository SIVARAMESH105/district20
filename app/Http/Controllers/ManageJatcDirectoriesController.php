<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\JatcDirectoriesCreateRequest;
use App\Http\Requests\JatcDirectoriesUpdateRequest;
use App\Models\Chapter;
use App\Models\Directories;
use App\Helpers\CommonHelper;
use Auth;

/**
 * Class ManageJatcDirectoriesController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Http\Requests\JatcDirectoriesCreateRequest
 * @package App\Http\Requests\JatcDirectoriesUpdateRequest
 * @package App\Models\Chapter
 * @package App\Models\Directories
 * @package App\Helpers\CommonHelper
 * @package Auth
 */
class ManageJatcDirectoriesController extends Controller
{
    /**
     * This function is used to get manage directories index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function index(Request $request)
    {
        $data['directories'] = Directories::all();
        $data['title'] = "Manage Jatc Directory";
        return view('manage_jatc_directories.index', $data);
    }
    /**
     * This function is used to get directories list ajax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function getJatcDirectoriesListAjax(Request $request)
    {
        return datatables()->of((new Directories)->getAllJatcDirectoriesAjax())
                        ->addColumn('action', function($directories){
                            $action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="'.route('manage-jatc-directories-edit', $directories->id) .'"><i class="fas fa-pencil-alt"></i></a>';
                            $action .=' <a class="btn btn-danger btn-xs delete-directory" href="javascript:;" data-id="'.$directories->id.'" data-url="'.route('manage-jatc-directories-delete', $directories->id) .'"><i class="fas fa-trash"></i></a></div>';
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
     * @author Techaffinity:vinothcl
     */
    public function add(Request $request)
    {   
        $data['title'] = "Manage Jatc Directory - Add";        
        $data['chapters'] = Chapter::all();
        return view('manage_jatc_directories.add', $data);
    }
    /**
     * This function is used to save manage directories
     *
     * @param JatcDirectoriesCreateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function save(JatcDirectoriesCreateRequest $request)
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
        $info['type'] = $request->type;
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
        if((new Directories)->createDirectoryWithType($info)) {         
            $request->session()->flash('success', "New Jatc Directory Created Successfully.");
            return redirect(route('manage-jatc-directories'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-jatc-directories'))->withInput();
        }
    }
    /**
     * This function is used to get edit manage directory page
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
            return redirect(route('manage-jatc-directories'))->withInput();
        }
        $data['chapters'] = Chapter::all();
        $data['info'] = $info = Directories::find($id);
        if(!$info) {
            $request->session()->flash('error', "Unable to find directory.");
            return redirect(route('manage-jatc-directories'))->withInput();
        }        
        $data['title'] = "Manage Jatc Directory - Edit";
        return view('manage_jatc_directories.edit', $data);
    }
    /**
     * This function is used to update manage directories
     *
     * @param JatcDirectoriesUpdateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function update(JatcDirectoriesUpdateRequest $request)
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
            $request->session()->flash('success', "Jatc Directory Updated Successfully.");
            return redirect(route('manage-jatc-directories'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-jatc-directories'))->withInput();
        }
    }
    /**
     * This function is used to delete manage directory
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
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
     * @author Techaffinity:vinothcl
     */
    public function view(Request $request)
    {
        $data['directories'] = Directories::all();
        $data['title'] = "JATC Directory";
        return view('manage_jatc_directories.view', $data);
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
            $data = CommonHelper::importJatcDirectory($request, 2);
            if($data) {
                 $request->session()->flash('success', "Jatc Directory Imported Successfully.");
                return redirect(route('manage-jatc-directories'));
            } else {
                $request->session()->flash('error', "Unable to read file (or) invalid input");
                return redirect(route('manage-jatc-directories'));
            }
        }
        $data['title'] = "Manage Jatc Directory - Import";
        return view('manage_jatc_directories.import', $data);        
    }
}