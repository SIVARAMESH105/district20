<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use App\Models\DeviceToken;
use App\Models\Event;
use Log;
use DB;
use Auth;

/**
 * Class NotificationHelper
 * namespace App\Helpers
 * @package Illuminate\Http\Request
 * @package App\Models\DeviceToken
 * @package App\Models\Event
 * @package Log
 * @package DB
 * @package Auth
 */
class NotificationHelper
{    
    /**
    * This function is used to send event created push notification
    *
    * @return bool
    * @author techaffinity:vinothcl
    */
    public static function sendEventCreatedPushNotification($event_id) {

        $tokens = DeviceToken::all();
        $androidTokens = [];
        $iosTokens = [];
        foreach($tokens as $token){
            if($token->device_type == 'android') {
                $androidTokens[] = $token->device_token;
            }
            if($token->device_type == 'ios') {
                $iosTokens[] = $token->device_token;
            }
        }
        $iosTokens = array_unique($iosTokens);
        $androidTokens = array_unique($androidTokens);
        Log::channel('notification')->info('---androidTokens');
        Log::channel('notification')->info(print_r($androidTokens, true));
        Log::channel('notification')->info('---iosTokens');
        Log::channel('notification')->info(print_r($iosTokens, true));
        self::callFireBaseFunction(1, $event_id, $androidTokens);
        self::callFireBaseFunction(2, $event_id, $iosTokens);        
    }
    public static function callFireBaseFunction($type, $event_id, $tokens) {
        Log::channel('notification')->info('---type---'.$type.'---'.($type==1?'Android':'IOS'));
        
        $event = Event::find($event_id);
        $desc = $event->event_description;
        $desc = (strlen($desc) > 153) ? substr($desc,0,150).'...' : $desc;
        $notification = [
            'title' => $event->event_title,
            'body'  => $desc,
            'sound' => true,
        ];
        $extraNotificationData = [
            "message" => $notification,
            "moredata" =>'dd',
            "event_id" => (int) $event_id
        ];
        $fcmNotification = [
            'registration_ids' => $tokens,
            'data' => $extraNotificationData
        ];        
        //for IOS
        if($type ==2){
            $fcmNotification['notification'] = $notification;
        }
        
        Log::channel('notification')->info(print_r($fcmNotification, true));

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $key= 'AAAAsnz0JR8:APA91bGY2eJ-1kkr0H1s6Y3RfBZ39dUWnWcGYPSHR_JJIFCzungocaKmaLo94mVXBiF5RvFMHkvVBH0IOCUWSC8ZXd3CZ4zOaFZHAM6oP6kEBjDSeG6PALGsap0H_qVzoqv5hrdmpzZJ';
        $headers = [
            "Authorization: key=$key",
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        Log::channel('notification')->info(print_r($result, true));
    }
}