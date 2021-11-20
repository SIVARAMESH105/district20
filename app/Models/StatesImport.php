<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class StatesImport extends Model
{
    public $table = 'states_import';
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
}
