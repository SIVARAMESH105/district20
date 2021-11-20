<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use AdminHelper;

class Notification extends Model
{
    public $table = 'notifications';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'description',
        'link',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'id';
    /**
     * This model function is used to get AllNotification Ajax
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllNotificationAjax() {
       return $this->select('id', 'title', 'link');
    }
    /**
     * This model function is used to get all events list in api
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllNotificationByUserApi($user_id='', $notification_id='') {
        return $this->selectRaw(DB::Raw('notifications.*, CASE
                                        WHEN EXISTS (SELECT 1 
                                                       FROM notification_actions as na
                                                       WHERE notifications.id = na.notification_id and
                                                             na.user_id='.$user_id.' and
                                                             na.seen=1)
                                        THEN 1
                                        ELSE 0
                                      END as seen'))
                        ->where(function($query) use ($notification_id){
                            if($notification_id)
                                $query->where('notifications.id', '=', $notification_id);                                      
                            return $query;
                        });
        // SELECT ..* , CASE
        //     WHEN EXISTS (SELECT 1 
        //                    FROM   notification_actions as na
        //                    WHERE  n.id = na.notification_id and na.user_id=59 and na.seen=1)
        //     THEN 1
        //     ELSE 0
        //   END as seen
        // FROM notifications as n
    }
     /**
     * This model function is used to create notification
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function createNotification($info) {
       return $this->create([
                                'title'=>$info['title'],
                                'description'=>$info['description'],
                                'link'=>$info['link'],
                                'created_by'=>$info['created_by'],
                                'created_at'=>date('Y-m-d H:s:i'),
                                'updated_at'=>date('Y-m-d H:s:i')
                            ]);
    }
    /**
     * This model function is used to update notification
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function updateNotification($info) {
       return $this->where('id', $info['id'])
                    ->update([
                            'title'=>$info['title'],
                            'description'=>$info['description'],
                            'link'=>$info['link'],
                            'updated_at'=>date('Y-m-d H:s:i')
                        ]);
    }
    /**
     * This model function is used to delete notification
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function deleteNotification($id) {
       return $this->where('id', $id)->delete();
    }
}