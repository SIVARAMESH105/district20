<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Users;
use App\Models\Directories;
use App\Models\Chapter;
use App\Models\ContractorResource;
use App\Models\Notification;
use App\Models\Event;
use App\Models\Language;
use App\Models\UsersRole;
use App\Events\ContactFormEvent;
use Auth;
use AdminHelper;
use Hash;

/**
 * Class UserController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Models\Users
 */
class UserController extends Controller
{    
	
    /**
     * This function is used to get users index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function index(Request $request)
    {
        $data['title'] = "Home";
        return view('user.index', $data);
    }
    /**
     * This function is used to get users contact page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function contact(Request $request)
    {
        if ($request->isMethod('post')) {
            //mail to chapter admin            
            if($request->name && $request->email && $request->message){
                $data['name'] = $request->name;
                $data['email'] = $request->email;
                $data['message'] = $request->message;
                $data['chapter_id'] = Auth::user()->chapter_id;
                event(new ContactFormEvent($data));
                $request->session()->flash('success', "Contact form submitted successfully!");
            } else {
                $request->session()->flash('error', "Something went wrong!");
            }            
        }
        $data['title'] = "Contact";
        return view('user.contact', $data);
    }
    /**
     * This function is used to get users settings page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function settings(Request $request)
    {
        $data['languages'] = Language::all();
        $data['font_size'] = [
                            '13' => 'Small',
                            '14' => 'Medium',
                            '15' => 'Large',
                        ];
        $data['user'] = Users::find(Auth::id());
        $data['title'] = "Settings";        
        return view('user.settings', $data);
    }

    /**
     * This function is used to get users settings page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function settingsPost(UserUpdateRequest $request)
    {
        if($request->name){
            $data['name'] = $request->name;
        }
        if($request->last_name){
            $data['last_name'] = $request->last_name;
        }
        if($request->language){
            $data['primary_language'] = $request->language;
        }
        if($request->font_size){
            $data['font_size'] = $request->font_size;
        }
        if($request->notification==1 || $request->notification==0){
            $data['notification'] = $request->notification;
        }
        if(Users::where('id', Auth::id())->update($data)){                
            $request->session()->flash('success', "Updated successfully!");
        } else {
            $request->session()->flash('error', "Something went wrong!");
        } 
        return redirect(route('user-settings'));
        
    }
    /**
     * This function is used to get users change password page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function changePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [ 
                'password' => 'required', 
                'new_password' => 'required',
            ]);
            if ($validator->fails()) {
                return redirect(route('user-change-password'))
                        ->withErrors($validator)
                        ->withInput();
            }
            if (Auth::attempt(array('email' => Auth::user()->email, 'password' => $request->password))){
                $new_password = Hash::make($request->new_password);
                $updated = Users::where('email', Auth::user()->email)->update(['password' => $new_password]);
                if($updated) {
                    $request->session()->flash('success', "Password updated successfully!.");                    
                } else {
                    $request->session()->flash('error', "Something went wrong!.");
                }
            } else {
                $request->session()->flash('error', "Current password is invalid!.");
            }
        }
        $data['title'] = "Change Password";
        return view('user.change_password', $data);
    }
    /**
     * This function is used to get users directory page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function directory(Request $request)
    {
        $data['title'] = "Directories";        
        $data['chapters'] = Chapter::all();
        $data['type'] = $request->type;
        $data['current_chapter'] = $request->chapter;
        $data['directories'] = (new Directories)->getUserDirectories($request->type, $request->chapter);
        return view('user.directory', $data);
    }
    /**
     * This function is used to get users resource page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function resource(Request $request)
    {
        $data['title'] = "Resources";
        $data['resources'] = (new ContractorResource)->getUserContractorResource();
        return view('user.resource', $data);
    }
    /**
     * This function is used to get users announcement page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function announcement(Request $request)
    {
        $data['title'] = "Announcements";        
        $notifications = (new Notification)->getAllNotificationByUserApi(Auth::id(), $request->notification_id);
        $data['announcements'] = $notifications->paginate(15);
        return view('user.announcement', $data);
    }    
    /**
     * This function is used to get users calendar page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function calendar(Request $request)
    {
        if($request->s) {
            $start = date('01-m-Y', strtotime("01-".$request->s)); 
        } else {
            $start = date('01-m-Y');
        }
        $data['start'] = date('d-m-Y', strtotime($start));
        $data['initialDate'] = date('Y-m-01', strtotime($start));
        $data['end'] = $end = date('t-m-Y', strtotime($start));
        $data['next'] = date('m-Y', strtotime("+1 months", strtotime($start)));
        $data['previous'] = date('m-Y', strtotime("-1 months", strtotime($start)));
        $events = (new Event)->getEventsByDates($start, $end);
        $eventsByDate = [];
        foreach($events as $event){
            $eventsByDate[date('d', strtotime($event->event_start_datetime))][] = $event;
        }
        $data['events'] = $eventsByDate;
        return view('user.calendar', $data);
    }
    /**
     * This function is used to get users agreement page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function agreement(Request $request)
    {
        $data['title'] = "View Documents";
        return view('user.agreement', $data);
    }    
}