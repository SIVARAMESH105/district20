<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\UsersRole;
use App\Models\Chapter;
use App\Events\NewUserCreatedEvent;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Auth;
use AdminHelper;

/**
 * Class ManageUserController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Models\Users
 * @package App\Models\UsersRole
 * @package App\Models\Chapter
 * @package App\Events\NewUserCreatedEvent
 * @package App\Http\Requests\UserCreateRequest
 * @package App\Http\Requests\UserUpdateRequest
 * @package Auth
 */
class ManageUserController extends Controller
{    
    /**
     * This function is used to get manage users index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function index(Request $request)
    {
        $data['role'] = $request->r;
        $data['roles'] = UsersRole::all();
        if(AdminHelper::checkUserIsSuperAdmin()){
            $data['title'] = "Manage Users";
            $data['addText'] = "Add User";
        } else {
            $data['title'] = "Manage Members";
            $data['addText'] = "Add Member";
        }
        return view('manage_users.index', $data);
    }
    /**
     * This function is used to get user list ajax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function getUserListAjax(Request $request)
    {
        $role = $request->role;
        return datatables()->of((new Users)->getAllUsersAjax($role))
                        ->addColumn('action', function($user){
                            $action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="'.route('manage-user-edit', $user->user_id) .'"><i class="fas fa-pencil-alt"></i></a>'; 
                            if($user->user_id!=Auth::id())
                                $action .=' <a class="btn btn-danger btn-xs delete-user" href="javascript:;" data-id="'.$user->user_id.'" data-url="'.route('manage-user-delete', $user->user_id) .'"><i class="fas fa-trash"></i></a></div>';
                            return $action;
                        })
                        ->editColumn('status', function($user){
                            if($user->status==1)
                                return "Active";
                            else
                                return "Inactive";
                        })
                        ->filterColumn('role_name', function($query, $keyword) {
                            $sql = "users_role.role_name like ?";
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                        })
                        ->rawColumns(['action', 'status'])
                        ->make(true);
    }
    /**
     * This function is used to get add manage user page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function add(Request $request)
    {
        $data['roles'] = UsersRole::all()->sortByDesc("id");
        $data['chapters'] = Chapter::all();
        if(AdminHelper::checkUserIsSuperAdmin()){
            $data['title'] = "Manage Users - Add";
            $data['brVal'] = "Manage Users";
        } else {
            $data['title'] = "Manage Members - Add";
            $data['brVal'] = "Manage Members";
        }
        return view('manage_users.add', $data);
    }
    /**
     * This function is used to get edit manage user page
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
            return redirect(route('manage-user'))->withInput();
        }
        $data['roles'] = UsersRole::all()->sortByDesc("id");
        $data['chapters'] = Chapter::all();
        $data['info'] = $info = Users::find($id);
        if(!$info) {
            $request->session()->flash('error', "Unable to find user.");
            return redirect(route('manage-user'))->withInput();
        }
        if(AdminHelper::checkUserIsSuperAdmin()){
            $data['title'] = "Manage Users - Edit";
            $data['brVal'] = "Manage Users";
        } else {
            $data['title'] = "Manage Members - Edit";
            $data['brVal'] = "Manage Members";
        }
        return view('manage_users.edit', $data);
    }
    /**
     * This function is used to save manage user
     *
     * @param UserCreateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function save(UserCreateRequest $request)
    {
        $info['name'] = $request->name;
        $info['email'] = $request->email;     
        $info['chapter'] = $request->chapter;
        $info['rnumber'] = $request->rnumber;
        $info['company_name'] = $request->company_name;
        $info['user_role'] = $request->user_role;
        $info['phone'] = $request->phone;
        $info['status'] = $request->status;

        $info['profile_pic'] = '';
        if($request->profile_pic) {
            $directory = public_path().'/users_pic';
            if (!is_dir($directory)) {
                mkdir($directory);
                chmod($directory, 0777);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_pic')->getClientOriginalName());
            $request->file('profile_pic')->move($directory, $imageName);
            $info['profile_pic'] = 'users_pic/'.$imageName;
        }
        if((new Users)->createUser($info)) {
            //event(new NewUserCreatedEvent($request, $info));            
            $request->session()->flash('success', "New User Created Successfully.");
            return redirect(route('manage-user'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-user'))->withInput();
        }
    }
    /**
     * This function is used to update manage user
     *
     * @param UserUpdateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function update(UserUpdateRequest $request)
    {
        $info['name'] = $request->name;
        $info['email'] = $request->email;     
        $info['chapter'] = $request->chapter;
        $info['rnumber'] = $request->rnumber;
        $info['user_role'] = $request->user_role;
        $info['company_name'] = $request->company_name;
        $info['status'] = $request->status;
        $info['phone'] = $request->phone;
        $info['id'] = $request->id;
        
        $info['profile_pic'] = '';
        if($request->profile_pic) {
            $directory = public_path().'/users_pic';
            if (!is_dir($directory)) {
                mkdir($directory);
                chmod($directory, 0777);
            }
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_pic')->getClientOriginalName());
            $request->file('profile_pic')->move($directory, $imageName);
            $info['profile_pic'] = 'users_pic/'.$imageName;
        }

        if((new Users)->updateUser($info)) {
            $request->session()->flash('success', "User Updated Successfully.");
            return redirect(route('manage-user'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-user'))->withInput();
        }
    }
    /**
     * This function is used to delete manage user
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if($id)
            return (new Users)->deleteUser($id);
        else
            return false;
    }
}