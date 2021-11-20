<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentLocation;
use DB;
use Auth;

class DocumentsImport extends Model 
{
    public $table = 'documents_import';   
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'document_name',
        'document_path',
        'document_type',
        'document_full_path',
        'expiration_date',
        'signature_required',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    protected $primaryKey = 'id';
}