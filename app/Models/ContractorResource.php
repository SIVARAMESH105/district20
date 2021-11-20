<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use AdminHelper;
use DB;

class ContractorResource extends Model
{
    public $table = 'contractor_resources';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'url',
        'description',
        'chapter_id',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'id';
    /**
     * This model function is used to get all ContractorResource
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllContractorResourceAjax() {
        $query = $this->select('*');
        if(AdminHelper::checkUserIsSuperAdmin()) {
            $query->get();
        } else { 
            $chapter_id = Auth::user()->chapter_id;
            $query->where('chapter_id', $chapter_id)->get();
        }
        return $query;
    }

    
    /**
     * This model function is used to create contractor rResource
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function createContractorResource($info) { 
        return $this->create([
            'chapter_id'=>$info['chapter'],
            'title'=>$info['title'],
            'description'=>$info['description'],
            'url'=>$info['url']
        ]);
    }
    /**
     * This model function is used to update contractor resource
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function updateContractorResource($info) {
        $data = [
                    'chapter_id'=>$info['chapter'],
                    'title'=>$info['title'],
                    'description'=>$info['description'],
                    'url'=>$info['url']
                ];
        return $this->where('id', $info['id'])->update($data);
    }    
    /**
     * This model function is used to delete user
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function deleteContractorResource($id) {
       return $this->where('id', $id)->delete();
    }
    /**
     * This model function is used to get User Contractor Resource
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getUserContractorResource() {
        $query = $this->select('*');
        if(!AdminHelper::checkUserIsSuperAdmin()) {
            $chapter_id = Auth::user()->chapter_id;
            $query->where('chapter_id', $chapter_id);
        }
        return $query->paginate(15);
    }
}