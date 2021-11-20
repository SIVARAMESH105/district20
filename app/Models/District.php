<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $table = 'districts';
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
