<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class UnionImport extends Model
{
    public $table = 'unions_import';
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
}
