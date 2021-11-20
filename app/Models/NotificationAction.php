<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use AdminHelper;

class NotificationAction extends Model
{
    public $table = 'notification_actions';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'notification_id',
        'user_id',
        'seen',
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'id';
    
    /**
     * This model function is used to created Or Update Notification Action
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function createdOrUpdateNotificationAction($notification_id, $user_id) {
        return $this::updateOrCreate(
	        							[
	        								'notification_id' => $notification_id,
	        								'user_id' => $user_id
	        							], 
						        		[
						        			'notification_id' => $notification_id,
						        			'user_id' => $user_id,
						        			'seen' => 1,
						        			'created_at' => date('Y-m-d H:i:s'),
						        			'updated_at' => date('Y-m-d H:i:s')
						        		]
					        		);
           
    }    
}
