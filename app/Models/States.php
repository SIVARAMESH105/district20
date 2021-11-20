<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class States extends Model
{
    public $table = 'states';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'chapter_id',
        'state_name',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id'; 

     /**
     * This model function is used to get the particular state information using chapter ID.
     * @param int $id
     * @return array
     * @author Techaffinity
     *
     */
    public function getDocumentState($id) {
        return $this->select('*')
                    ->Where('chapter_id', $id)
                    ->get();
    }
    /**
     * This model function is used to get chapter states.
     * @param int $chapter_id
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getChapterStates($chapter_id) {
        return $this->select('*')
                    ->Where('chapter_id', $chapter_id);
    }
}
