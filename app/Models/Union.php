<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class Union extends Model
{
    public $table = 'unions';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'state_id',
        'union_name',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id'; 

    /**
     * This model function is used to get the particular union information using state ID.
     * @param int $id
     * @return array
     * @author Techaffinity
     */
    public function getDocumentUnion($id) {
        return $this->select('*')
                    ->Where('state_id', $id)
                    ->get();
    }
    public function getDocumentUnionGroup($ids) {
        return $this->select('*')
                    ->WhereIn('state_id', $ids)
                    ->get();
    }
    /**
     * This model function is used to get state unions.
     * @param int $state_id
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getStateUnions($state_id) {
        return $this->select('unions.*')
                    ->Where('state_id', $state_id);
    }
    /**
     * This model function is used to get chapter unions.
     * @param int $chapter_id
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getChapterUnions($chapter_id) {
        return $this->select('unions.*')
                    ->leftjoin('states', 'unions.state_id', 'states.id')
                    ->Where('states.chapter_id', $chapter_id);
    }
}
