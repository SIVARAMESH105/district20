<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Helpers\CommonHelper;

/**
 * Class EventController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Http\Controllers\Controller
 * @package App\Models\Event
 * @package App\Helpers\CommonHelper
 */
class EventController extends Controller 
{
    public $successCode = 200;
    public $errorCode = 401;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->base_url = url('/').'/';
    }
    /**
     * This function is used to get events
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getEvents(Request $request) { 
        $query = (new Event)->getAllEventsApi($request->event_id, $request->chapter_id, $request->date);
        $events = CommonHelper::customPagination($query);
    	if(count($events)) {
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'Events are available',
                                        'data' => $events
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'data' => [],
                                        'message'=>'Empty events!.',
                                    ], $this->errorCode);
        }
    }
}