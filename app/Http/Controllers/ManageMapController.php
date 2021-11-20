<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documents;
use App\Models\UsersRole;
use App\Models\States;
use App\Models\Union;  
use App\Models\Representative;
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
 * @package App\Models\Documents
 * @package App\Models\UsersRole
 * @package App\Models\Chapter
 * @package App\Events\NewUserCreatedEvent
 * @package App\Http\Requests\UserCreateRequest
 * @package App\Http\Requests\UserUpdateRequest
 * @package Auth
 */
class ManageMapController extends Controller
{   

    
    /**
     * This function is used to get manage document index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity
     */

    public function index(Request $request)
    {
         $data['title'] = "Manage Maps";
        return view('manage_maps.index',$data);
    }
    
   
}