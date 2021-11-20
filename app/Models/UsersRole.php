<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class UsersRole extends Model
{
    public $table = 'users_role';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'role_name',
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'id';
    
}
