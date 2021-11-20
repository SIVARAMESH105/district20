<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use Hash;
use AdminHelper;

class Users extends Model
{
    public $table = 'users';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'user_role',
        'chapter_id',
        'registration_number',
        'phone',
        'profile_pic',
        'company_name',
        'status',
        'primary_language',
        'font_size',
        'notification',
        'created_at',
        'updated_at', 
    ];

    protected $primaryKey = 'id';
    /**
     * This model function is used to get all users lists
     *
     * @return array
     * @author Techaffinity:vinothcl
     */
    public function getAllUsersAjax($role='') {
       $query = $this->select('*', 'users.id as user_id')
                    ->leftjoin('users_role', 'users.user_role', 'users_role.id');
        if(AdminHelper::checkUserIsOnlyChapterAdmin()){
            $chapter_id = Auth::user()->chapter_id;
            $query->where('users.chapter_id', $chapter_id);
        }
        if($role){
            $query->where('users.user_role', $role);
        }
        return $query;
    }
    
    /**
     * This model function is used to create user
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function createUser($info) {

       return $this->create([
                                'name'=>$info['name'],
                                'email'=>$info['email'],
                                'chapter_id'=>$info['chapter'],
                                'company_name'=>$info['company_name'],
                                'registration_number'=>$info['rnumber'],
                                'user_role'=>$info['user_role'],
                                'phone'=>isset($info['phone'])?$info['phone']:'',
                                'profile_pic'=>isset($info['profile_pic'])?$info['profile_pic']:'',
                                'status'=>$info['status'],
                                'password'=>isset($info['password'])?$info['password']:Hash::make(rand(54542,55464)),
                            ]);
    }
    /**
     * This model function is used to update user
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function updateUser($info) {
        $data = [
                    'name'=>$info['name'],
                    'email'=>$info['email'],
                    'chapter_id'=>$info['chapter'],
                    'company_name'=>$info['company_name'],
                    'registration_number'=>$info['rnumber'],
                    'phone'=>isset($info['phone'])?$info['phone']:'',
                    'user_role'=>$info['user_role'],
                    'status'=>$info['status'],
                ];
        if(isset($info['profile_pic']) && $info['profile_pic']!=''){
            $data['profile_pic'] = $info['profile_pic'];
        }
       return $this->where('id', $info['id'])
                    ->update($data);
    }    
    /**
     * This model function is used to delete user
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function deleteUser($id) {
       return $this->where('id', $id)->delete();
    }
    /**
     * This model function is used to get chapter admin emails
     *
     * @return bool
     * @author Techaffinity:vinothcl
     */
    public function getChapterAdminEmails($chapter_id) {
       return $this->where('chapter_id', $chapter_id)
                    ->where('user_role', 2)->pluck('email')->toArray();
    }    
}
