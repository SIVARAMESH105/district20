<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class DocumentLocationImport extends Model 
{
    public $table = 'document_locations_import';   
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'document_id',
        'chapter_id',
        'state_id',
        'union_id',        
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'id';
}