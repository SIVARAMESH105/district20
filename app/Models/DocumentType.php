<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model 
{
    public $table = 'document_types';   
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'document_type_name',
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'id';
    /**
     * This model function is used to get all Document Type lists
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllDocumentTypeAjax() {
       return $this->select('*');
    }
     /**
     * This model function is used to create document type
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function createDocumentType($info) {
       return $this->create([
                                'document_type_name'=>$info['document_type_name'],
                                'created_at'=>date('Y-m-d H:s:i'),
                                'updated_at'=>date('Y-m-d H:s:i')
                            ]);
    }
    /**
     * This model function is used to update document type
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function updateDocumentType($info) {
       return $this->where('id', $info['id'])
                    ->update([
                                'document_type_name'=>$info['document_type_name'],
                                'updated_at'=>date('Y-m-d H:s:i')
                            ]);
    }
    /**
     * This model function is used to delete Document Type
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function deleteDocumentType($id) {
       return $this->where('id', $id)->delete();
    }
    
    
}
