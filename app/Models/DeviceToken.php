<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class DeviceToken extends Model
{
    public $table = 'device_tokens';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'email_id',
        'device_token',
        'device_type',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';    
}