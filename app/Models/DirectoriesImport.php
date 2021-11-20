<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use AdminHelper;
use DB;

class DirectoriesImport extends Model
{
    public $table = 'directories_import';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'chapter_id',
        'type',
        'name',
        'position',
        'address',
        'state',
        'district',        
        'city',
        'zipcode',
        'phone',
        'fax',
        'contact',
        'email',
        'website',
        'profile_pic',
        'chapter_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];
    protected $primaryKey = 'id';
    
}
