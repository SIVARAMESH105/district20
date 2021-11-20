<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Event;
use App\Http\Requests\EventCreateRequest;
use App\Helpers\NotificationHelper;
use AdminHelper;

/**
 * Class ManageEventController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Models\Chapter
 * @package App\Models\Event
 * @package App\Http\Requests\EventCreateRequest
 * @package App\Helpers\NotificationHelper
 * @package AdminHelper
 */
class ManageEventController extends Controller
{    
    /**
     * This function is used to get manage event index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function index()
    {
        $data['title'] = "Manage Events";
        $data['chapters'] = Chapter::all();
        return view('manage_events.index', $data);
    }
    
    /**
     * This function is used to get event list ajax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function getEventListAjax(Request $request)
    {
        return datatables()->of((new Event)->getAllEventsAjax())
                        ->addColumn('action', function($event){                            
                            $action = '<div class="action-btn"><a class="btn btn-primary btn-xs" title="View" href="'.route('view-user-events-details', ['id' => $event->id]) .'"><i class="fas fa-eye"></i></a>';
                            $action .= '<div class="action-btn"><a class="btn btn-info btn-xs" title="Edit" href="'.route('manage-event-edit', $event->id) .'"><i class="fas fa-pencil-alt"></i></a>'; 
                            $action .=' <a class="btn btn-danger btn-xs delete-event" href="javascript:;" data-id="'.$event->id.'" data-url="'.route('manage-event-delete', $event->id) .'"><i class="fas fa-trash"></i></a></div>';                            
                            return $action;
                        })
                        ->addColumn('view', function($event){
                            return '<div class="action-btn"><a class="btn btn-primary btn-xs" title="Edit" href="'.route('view-user-events-details', $event->id) .'"><i class="fas fa-eye"></i></a>';
                        })
                        ->editColumn('status', function($event){
                            if($event->status==1)
                                return "Active";
                            else
                                return "Inactive";
                        })
                        ->filterColumn('chapter_name', function($query, $keyword) {
                            $sql = "chapters.chapter_name like ?";
                            $query->whereRaw($sql, ["%{$keyword}%"]);
                        })
                        ->rawColumns(['action', 'status', 'view'])
                        ->make(true);
    }
    /**
     * This function is used to get manage event index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function viewEventsAdmin()
    {
        $data['title'] = "Manage Events";
        $data['chapters'] = Chapter::all();
        $events = (new Event)->getAllEventsAjax()->get();
        $eventstr = '[  ';
        foreach($events as $event){
            $color = $event->status==1?"#3c8dbc":"#e87d7d";
            $eventstr .= "{
                              title          : '".$event->event_title."',
                              start          : new Date('".$event->event_start_datetime."'),
                              end            : new Date('".$event->event_end_datetime."'),
                              allDay         : false,
                              url            : '".route('manage-event-edit', ['id' => $event->id])."',
                              backgroundColor: '".$color."',
                              borderColor    : '".$color."',
                              description: '".$event->event_description."'
                            },";
        }
        $eventstr .= " ]";
        $data['events'] = $eventstr;
        return view('manage_events.event_admin_calendar_view', $data);
    }

    /**
     * This function is used to get manage event index page
     *
     * @param EventCreateRequest $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function save(EventCreateRequest $request)
    {
        $info['event_title'] = $request->event_title;
        $info['event_location'] = $request->event_location;
        $info['event_start_date_val'] = date("Y-m-d H:i:s", strtotime($request->event_start_date_val));
        $info['event_end_date_val'] = date("Y-m-d H:i:s", strtotime($request->event_end_date_val));
        $info['event_description'] = $request->event_description;
        $info['chapter'] = $request->chapter;
        $info['status'] = $request->status;
        $info['event_link'] = $request->event_link;
        $eventCreated = (new Event)->createEvent($info);
        if($eventCreated) {
            // push notification
            NotificationHelper::sendEventCreatedPushNotification($eventCreated->id);
        }
        if($request->isAjax) {
            if($eventCreated) {
                return json_encode(['status'=>1, 'message'=>'success']);
            } else {            
                return json_encode(['status'=>1, 'message'=>'failure']);
            }
        } else {
            if($eventCreated) {
                $request->session()->flash('success', "New Event Created Successfully.");
                return redirect(route('manage-event'));
            } else {            
                $request->session()->flash('error', "Nothing to update (or) unable to update.");
                return redirect(route('manage-event'))->withInput();
            }
        }        
    }
    /**
     * This function is used to get manage event edit page
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
            return redirect(route('manage-event'))->withInput();
        }
        $data['chapters'] = Chapter::all();
        $data['info'] = $info = Event::find($id);
        if(!$info) {
            $request->session()->flash('error', "Unable to find event.");
            return redirect(route('manage-event'))->withInput();
        }
        $data['title'] = "Manage Events - Edit";
        return view('manage_events.edit', $data);
    }
    /**
     * This function is used to update event 
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function update(EventCreateRequest $request)
    {
        $info['event_title'] = $request->event_title;
        $info['event_location'] = $request->event_location;
        $info['event_start_date_val'] = date("Y-m-d H:i:s", strtotime($request->event_start_date_val));
        $info['event_end_date_val'] = date("Y-m-d H:i:s", strtotime($request->event_end_date_val));
        $info['event_description'] = $request->event_description;
        $info['chapter'] = $request->chapter;
        $info['status'] = $request->status;
        $info['event_link'] = $request->event_link;
        $id = $info['id'] =$request->id;
        if(!$id){            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-event'))->withInput();
        }
        $eventCreated = (new Event)->updateEvent($info);
        if($eventCreated) {
            $request->session()->flash('success', "New Event Updated Successfully.");
            return redirect(route('manage-event'));
        } else {            
            $request->session()->flash('error', "Nothing to update (or) unable to update.");
            return redirect(route('manage-event'))->withInput();
        }
    }
    /**
     * This function is used to delete manage event
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function delete(Request $request)
    {
        $id = $request->id;
        if($id)
            return (new Event)->deleteEvent($id);
        else
            return false;
    }

    /**
     * This function is used to display the user event view page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function listView()
    {
        $data['title'] = "View Events";
        $data['chapters'] = Chapter::all();
        return view('manage_events.event_list_view', $data);
    }
    /**
     * This function is used to display the user event view details page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function viewDetails(Request $request)
    {
        $id = $request->id;
        if(!$id){
            $request->session()->flash('error', "Something Went Wrong!.");
            return redirect(route('manage-event'))->withInput();
        }
        $data['info'] = $info = Event::find($id);
        if(!$info) {
            $request->session()->flash('error', "Unable to find event.");
            return redirect(route('manage-event'))->withInput();
        }
        $data['title'] = "Event Details";
        $data['chapter'] = Chapter::find($info['chapter_id']);
        $data['brLink'] = AdminHelper::checkUserIsOnlyUserAdmin()?route('view-user-events'):route('manage-event');
        return view('manage_events.event_details', $data);
    }
    /**
     * This function is used to get manage event index page
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:vinothcl
     */
    public function viewEventsUsers()
    {
        $data['title'] = "Events";
        $events = (new Event)->getAllEventsAjax()->get();
        $eventstr = '[  ';
        foreach($events as $event){
            $color = $event->status==1?"#3c8dbc":"#e87d7d";
            $eventstr .= "{
                              title          : '".$event->event_title."',
                              start          : new Date('".$event->event_start_datetime."'),
                              end            : new Date('".$event->event_end_datetime."'),
                              allDay         : false,
                              url            : '".route('view-user-events-details', ['id' => $event->id])."',
                              backgroundColor: '".$color."',
                              borderColor    : '".$color."',
                              description: '".$event->event_description."'
                            },";
        }
        $eventstr .= " ]";
        $data['events'] = $eventstr;
        return view('manage_events.event_user_calendar_view', $data);
    }
}