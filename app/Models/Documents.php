<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentLocation;
use DB;
use Auth;

class Documents extends Model 
{
    public $table = 'documents';   
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
    /**
     * This model function is used to get all Document lists
     *
     * @return array
     * @author Techaffinity:sivaramesh
     */
    public function getAllDocumentsAjax($chapter_id, $state_id ='', $union_id='') {
        if($chapter_id== "all") {
            $query = DB::table('documents')->get();
            return $query;
        } else {
           $query = DB::table('documents')
                                ->whereIn('id', 
                                                DocumentLocation::where('chapter_id', $chapter_id)
                                                ->groupBy('document_id')
                                                ->pluck('document_id'))
                                ->get();
           if($state_id) {
                $query = DB::table('documents')
                                ->whereIn('id', 
                                                DocumentLocation::where('state_id', $state_id)
                                                ->groupBy('document_id')
                                                ->pluck('document_id'))
                                ->get();
           } 
           if($union_id) {
            $query = DB::table('documents')
                                ->whereIn('id', 
                                                DocumentLocation::where('union_id', $union_id)
                                                ->groupBy('document_id')
                                                ->pluck('document_id'))
                                ->get();
           }
           return $query;
        }       
    }
     /**
     * This model function is used to create Document
     *
     * @return bool
     * @author Techaffinity:sivaramesh
     */
     public function createDocument($info) {       
        $user_id = Auth::user()->id;
        return $this->insertGetId([
            'document_name'=>$info['document_name'],
            'document_path'=>$info['document_path'],
            'document_type'=>$info['document_type'],
            'created_by'=>$user_id,
            'updated_by'=>$user_id,
            'created_at'=>date('Y-m-d H:s:i'),
            'updated_at'=>date('Y-m-d H:s:i')
        ]);
    }
    /**
     * This model function is used to update document
     *
     * @return bool
     * @author Techaffinity:sivaramesh
     */
    public function updateDocument($info) {
        $user_id = Auth::user()->id;
        return $this->where('id', $info['id'])
                    ->update([
                                'document_name'=>$info['document_name'],
                                'document_path'=>$info['document_path'],
                                'document_type'=>$info['document_type'],
                                'created_by'=>$user_id,
                                'updated_by'=>$user_id,
                                'updated_at'=>date('Y-m-d H:s:i')
                            ]);
    }
    /**
     * This model function is used to delete document
     *
     * @return bool
     * @author Techaffinity:sivaramesh
     */
    public function deleteDocument($id) {
       return $this->where('id', $id)->delete();
    }
    /**
     * This model function is used to get all Document lists
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getDocuments($chapter_id='', $state_id ='', $union_id='', $document_type='', $q='') {
        $documentIDs = DB::table('document_locations as dl')
                    ->leftjoin('chapters as c', 'c.id', 'dl.chapter_id')
                    ->leftjoin('states as s', 's.id', 'dl.state_id')
                    ->leftjoin('unions as u', 'u.id', 'dl.union_id')
                    ->leftjoin('documents as d', 'd.id', 'dl.document_id')
                    ->where(function($query) use ($chapter_id, $state_id, $union_id){
                        if($chapter_id)
                            $query->where('dl.chapter_id', '=', $chapter_id);
                        if($state_id)
                            $query->where('dl.state_id', '=', $state_id);
                        if($union_id)
                            $query->where('dl.union_id', '=', $union_id);                                         
                        return $query;
                    })
                    ->where(function($query) use ($q){
                        if($q) {
                            $query->where('c.chapter_name', 'LIKE', '%'.$q.'%');
                            $query->orWhere('s.state_name', 'LIKE', '%'.$q.'%');
                            $query->orWhere('u.union_name', 'LIKE', '%'.$q.'%');
                        }                
                        return $query;
                    })
                    ->where(function($query) use ($document_type){
                        if($document_type)
                            $query->where('d.document_type', '=', $document_type);                                          
                        return $query;
                    })
                    ->groupBy('document_id')
                    ->pluck('document_id');
        return DB::table('documents')->select('documents.*')->whereIn('id', $documentIDs);         
    }
    /**
     * This model function is used to get all Document lists
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getDocuments_bkp($chapter_id='', $state_id ='', $union_id='', $document_type='', $q='') {
        return DB::table('documents')
                    ->select('documents.*')
                    ->leftjoin('chapters as c', 'c.id', 'documents.chapter_id')
                    ->leftjoin('states as s', 's.id', 'documents.state_id')
                    ->leftjoin('unions as u', 'u.id', 'documents.union_id')
                    ->where(function($query) use ($chapter_id, $state_id, $union_id, $document_type){
                        if($chapter_id)
                            $query->where('documents.chapter_id', '=', $chapter_id);
                        if($state_id)
                            $query->where('documents.state_id', '=', $state_id);
                        if($union_id)
                            $query->where('documents.union_id', '=', $union_id);
                        if($document_type)
                            $query->where('documents.document_type', '=', $document_type);                                          
                        return $query;
                    })
                    ->where(function($query) use ($q){
                        if($q) {
                            $query->where('c.chapter_name', 'LIKE', '%'.$q.'%');
                            $query->orWhere('s.state_name', 'LIKE', '%'.$q.'%');
                            $query->orWhere('u.union_name', 'LIKE', '%'.$q.'%');
                        }                
                        return $query;
                    });    
    }
    /**
     * This model function is used to get all Document lists
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getDocumentById($id) {
        return DB::table('documents')->where('id', '=', $id)->get();     
    }
}