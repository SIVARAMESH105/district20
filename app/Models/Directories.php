<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use AdminHelper;
use DB;

class Directories extends Model
{
    public $table = 'directories';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'chapter_id',
        'type',
        'name',
        'position',
        'address',
        'state',
        'district',        
        'city',
        'zipcode',
        'phone',
        'fax',
        'contact',
        'email',
        'website',
        'profile_pic',
        'chapter_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];
    protected $primaryKey = 'id';
    /**
     * This model function is used to get all users lists
     *
     * @return array
     * @author Techaffinity:syamala
     */
    public function getAllDirectoriesAjax() {
        $query = $this->select('*')->where('type', 1);
        if(AdminHelper::checkUserIsSuperAdmin()) {
            $query->get();
        } else { 
            $chapter_id = Auth::user()->chapter_id;
            $query->where('chapter_id', $chapter_id)->get();
        }
        return $query;
    }
    /**
     * This model function is used to get All Jatc Directories Ajax
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllJatcDirectoriesAjax() {
        $query = $this->select('*')->where('type', 2);
        if(AdminHelper::checkUserIsSuperAdmin() ){
            $query->get();
        }
        else{ 
           $chapter_id = Auth::user()->chapter_id;
           $query->where('chapter_id', $chapter_id)->get();
        }
        return $query->get();
    }
    /**
     * This model function is used to get All Ibew Directories Ajax
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllIbewDirectoriesAjax() {
        $query = $this->select('*')->where('type', 3);
        // if(AdminHelper::checkUserIsSuperAdmin() ){
        //     $query->get();
        // }
        // else{ 
        //    $chapter_id = Auth::user()->chapter_id;
        //    $query->where('chapter_id', $chapter_id)->get();
        // }
        return $query->get();
    }

    /**
     * This model function is used to get All Ibew Directories Ajax
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllChapterDirectoriesAjax() {
        $query = $this->select('*')->where('type', 4);
        if(AdminHelper::checkUserIsSuperAdmin() ){
            $query->get();
        }
        else{ 
           $chapter_id = Auth::user()->chapter_id;
           $query->where('chapter_id', $chapter_id)->get();
        }
        return $query->get();
    }

    
    /**
     * This model function is used to create user
     *
     * @return bool
     * @author Techaffinity:syamala
     */
    public function createDirectory($info) {        
        $user_id = Auth::user()->id;
        return $this->create([
            'chapter_id'=>$info['chapter'],
            'name'=>$info['name'],
            'position'=>$info['position'],
            'address'=>$info['address'],
            'state'=>$info['state'],
            'city'=>$info['city'],
            'zipcode'=>$info['zipcode'],
            'phone'=>$info['phone'],
            'fax'=>$info['fax'],
            'contact'=>$info['contact'],
            'email'=>$info['email'],
            'website'=>$info['website'],
            'profile_pic'=>$info['profile_pic'],            
            'created_by'=>$user_id,
            'updated_by'=>$user_id,
        ]);
    }
    /**
     * This model function is used to create user
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function createDirectoryWithType($info) {        
        $user_id = Auth::user()->id;
        return $this->create([
            'chapter_id'=>$info['chapter'],
            'name'=>$info['name'],
            'position'=>$info['position'],
            'address'=>$info['address'],
            'state'=>$info['state'],
            'district'=>isset($info['district'])?$info['district']:0,
            'city'=>$info['city'],
            'zipcode'=>$info['zipcode'],
            'phone'=>$info['phone'],
            'fax'=>$info['fax'],
            'contact'=>$info['contact'],
            'email'=>$info['email'],
            'website'=>$info['website'],
            'type'=>$info['type'],
            'profile_pic'=>$info['profile_pic'],
            'created_by'=>$user_id,
            'updated_by'=>$user_id,
        ]);
    }
    /**
     * This model function is used to update directories
     *
     * @return bool
     * @author Techaffinity:syamala
     */
    public function updateDirectory($info) {
    	$user_id = Auth::user()->id;
        $data = [
                    'chapter_id'=>$info['chapter'],
                    'district'=>isset($info['district'])?$info['district']:0,
                    'name'=>$info['name'],
                    'position'=>$info['position'],
                    'address'=>$info['address'],
                    'state'=>$info['state'],
                    'city'=>$info['city'],
                    'zipcode'=>$info['zipcode'],
                    'phone'=>$info['phone'],
                    'fax'=>$info['fax'],
                    'contact'=>$info['contact'],
                    'email'=>$info['email'],
                    'website'=>$info['website'],
                    'website'=>$info['website'],
                    'created_by'=>$user_id,
                    'updated_by'=>$user_id,
                ];
        if($info['profile_pic']) {
            $data['profile_pic'] = $info['profile_pic'];
        }
        return $this->where('id', $info['id'])
                    ->update($data);
    }    
    /**
     * This model function is used to delete user
     *
     * @return bool
     * @author Techaffinity:syamala
     */
    public function deleteDirectory($id) {
       return $this->where('id', $id)->delete();
    }
    /**
     * This model function is used to get directory by type and chapterId
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getDirectoryByTypeAndChapterId($type='', $chapter_id='', $state='') {
       return DB::table('directories')->where(function($query) use ($type, $chapter_id, $state)
                {
                    if($type) {
                        $query->where('type', $type);
                    }
                    if($chapter_id) {
                        $query->where('chapter_id', $chapter_id);
                    }
                    if($state) {
                        $query->where('state', 'LIKE', '%'.$state.'%');
                    }
                    return $query;
                });
    }
    /**
     * This model function is used to get All Directories
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getUserDirectories($type='', $chapter='') {
        $query = $this->select('*');
        if($type)
            $query->where('type', $type);
        if($chapter)
            $query->where('chapter_id', $chapter);

        return $query->paginate(15);
    }
}