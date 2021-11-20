<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use AdminHelper;

class Event extends Model
{
    public $table = 'events';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'chapter_id',
        'event_title',
        'event_description',
        'event_location',
        'event_start_datetime',
        'event_end_datetime',
        'status',
        'event_link',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    protected $primaryKey = 'id';
    
    /**
     * This model function is used to create event
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function createEvent($info) {
       return $this->create([
                                'chapter_id' => $info['chapter'],
                                'event_title' => $info['event_title'],
                                'event_description' => $info['event_description'],
                                'event_location' => $info['event_location'],
                                'event_start_datetime' => $info['event_start_date_val'],
                                'event_end_datetime' => $info['event_end_date_val'],
                                'status' => $info['status'],
                                'event_link' => $info['event_link'],
                                'created_at' =>  date("Y-m-d H:i:s"),
                                'updated_at' =>  date("Y-m-d H:i:s"),
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id()
                            ]);
    }
    /**
     * This model function is used to update event
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function updateEvent($info) {
       return $this->where('id', $info['id'])
                   ->update([
                                'chapter_id' => $info['chapter'],
                                'event_title' => $info['event_title'],
                                'event_description' => $info['event_description'],
                                'event_location' => $info['event_location'],
                                'event_start_datetime' => $info['event_start_date_val'],
                                'event_end_datetime' => $info['event_end_date_val'],
                                'status' => $info['status'],
                                'event_link' => $info['event_link'],
                                'updated_at' =>  date("Y-m-d H:i:s"),
                                'updated_by' => Auth::id()
                            ]);
    }    
    /**
     * This model function is used to get all events lists
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllEventsAjax() {
       $query = $this->select('events.*','chapters.chapter_name as chapter_name')->leftjoin('chapters', 'chapter_id', 'chapters.id');
       if(AdminHelper::checkUserIsOnlyChapterAdmin() || AdminHelper::checkUserIsOnlyUserAdmin() ){
            $chapter_id = Auth::user()->chapter_id;
            $query->where('events.chapter_id', $chapter_id);
        }
        return $query;
    }
    /**
     * This model function is used to delete event
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function deleteEvent($id) {
       return $this->where('id', $id)->delete();
    }
    /**
     * This model function is used to get all events list in api
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllEventsApi($event_id='', $chapter_id='', $date='') {
        $dateEx = $date?explode('-', $date):null;
        return $this->where(function($query) use ($event_id, $chapter_id, $dateEx){
                        if($event_id)
                            $query->where('id', '=', $event_id);
                        if($chapter_id)
                            $query->where('chapter_id', '=', $chapter_id);
                        if($dateEx){
                            //$query->whereRaw(DB::RAW("DATE_FORMAT(event_start_datetime, '%Y-%m-%d') = DATE('".$date."')"));
                            $query->whereRaw(DB::RAW("YEAR(event_start_datetime) = ".$dateEx[0]." AND MONTH(event_start_datetime) = ".$dateEx[1]));
                        }
                        return $query;
                    });
    }
    /**
     * This model function is used to get all by dates
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getEventsByDates($start, $end){
        $dateEx = $start?explode('-', $start):null;
        return $this->getAllEventsAjax()
                    ->whereRaw(DB::RAW("YEAR(event_start_datetime) = ".$dateEx[2]." AND MONTH(event_start_datetime) = ".$dateEx[1]))
                    ->get();
    }
}
