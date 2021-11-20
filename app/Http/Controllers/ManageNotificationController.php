<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Requests\NotificationCreateRequest;
use Auth;

/**
 * Class ManageNotificationController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Models\Notification
 * @package App\Http\Requests\NotificationCreateRequest
 * @package Auth
 */
class ManageNotificationController extends Controller
{    
    /**
     * This function is used to get Announcement index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function index(Request $request)
    {
        $data['title'] = "Manage Announcement";
        return view('manage_notification.index', $data);
    }
    /**
     * This function is used to get Notification List Ajax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function getNotificationListAjax(Request $request)
    {
        return datatables()->of((new Notification)->getAllNotificationAjax())
                            ->addColumn('action', function($dt){
                                $action = '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="'.route('manage-notification-edit', $dt->id) .'"><i class="fas fa-pencil-alt"></i></a>';
                                    $action .=' <a class="btn btn-danger btn-xs delete-notification" href="javascript:;" data-id="'.$dt->id.'" data-url="'.route('manage-notification-delete', $dt->id) .'"><i class="fas fa-trash"></i></a></div>';
                                return $action;
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }
    /**
     * This function is used to get add notifications page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function add(Request $request)
    {
        $data['title'] = "Manage Announcement - Add";
        return view('manage_notification.add', $data);
    }
    /**
     * This function is used to save notifications
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function save(NotificationCreateRequest $request)
    {
    	$info['title'] = $request->title;
    	$info['description'] = $request->desc;
    	$info['link'] = $request->link;
    	$info['created_by'] = Auth::id();
        if((new Notification)->createNotification($info)) {
            $request->session()->flash('success', "New Announcement Created Successfully.");
            return redirect(route('manage-notification'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-notification'))->withInput();
        }
    }
    /**
     * This function is used to get edit manage notification page
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
            return redirect(route('manage-notification'))->withInput();
        }
        $data['info'] = Notification::find($id);
        $data['title'] = "Manage Announcement - Edit";
        return view('manage_notification.edit', $data);
    }
    
    /**
     * This function is used to update notification
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function update(NotificationCreateRequest $request)
    {
        if (!$request->id) {
            return back()->withInput()->withErrors(['id'=>'Something went wrong!.']);
        }
        $info['id'] = $request->id;
        $info['title'] = $request->title;
    	$info['description'] = $request->desc;
    	$info['link'] = $request->link;
    	
        if((new Notification)->updateNotification($info)) {
            $request->session()->flash('success', "Notification Updated Successfully.");
            return redirect(route('manage-notification'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-notification'))->withInput();
        }
    }
    /**
     * This function is used to delete notification
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if($id)
            return (new Notification)->deleteNotification($id);
        else
            return false;
    }
}