<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StateCode extends Model
{
    public $table = 'state_codes';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'value',
        'name',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';
}
