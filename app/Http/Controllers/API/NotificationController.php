<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationAction;
use App\Helpers\CommonHelper;
use Auth;

/**
 * Class NotificationController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request; 
 * @package App\Http\Controllers\Controller
 * @package App\Models\Notification
 * @package App\Models\NotificationAction
 * @package App\Helpers\CommonHelper
 * @package Auth
 */
class NotificationController extends Controller 
{
    public $successCode = 200;
    public $errorCode = 401;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->base_url = url('/').'/';
    }
    /**
     * This function is used to get all notification
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getAllNotification(Request $request) { 
    	$userId = Auth::id();
        $query = (new Notification)->getAllNotificationByUserApi($userId, $request->notification_id);
        $notifications = CommonHelper::customPagination($query);
    	if(count($notifications)) {
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'Notifications are available',
                                        'data' => $notifications
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Empty notifications!.',
                                        'data' => [],
                                    ], $this->successCode);
        }
    }
    /**
     * This function is used to create notification action
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function notificationAction(Request $request) { 
        $userId = Auth::id();
    	if(!$request->notification_id){
    		return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'NotificationsId or UserId not found!.',
                                    ], $this->errorCode);
    	}
        $action = (new NotificationAction)->createdOrUpdateNotificationAction($request->notification_id, $userId);
    	if($action) {
            return response()->json([
                                    'status'=>1,
                                    'base_url' => $this->base_url,
                                    'message'=>'success',
                                ], $this->successCode); 
        } else { 
        	return response()->json([
                                    'status'=>0,
                                    'base_url' => $this->base_url,
                                    'message'=>'failure',
                                ], $this->errorCode);
        }
    }
    
}