<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use App\Models\Users;
use App\Models\DeviceToken;
use App\Models\Language;
use Validator;
use Hash;

/**
 * Class UserController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Http\Controllers\Controller
 * @package Illuminate\Support\Facades\Auth
 * @package App\Models\Users
 * @package Validator
 * @package Hash
 */
class UserController extends Controller 
{
    public $successCode = 200;
    public $errorCode = 401;
    public $font_size = [
                            '1' => 'Small',
                            '2' => 'Medium',
                            '3' => 'Large',
                        ];
    
    use SendsPasswordResetEmails;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->base_url = url('/').'/';
    }
    /**
     * This function is used to check user login
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user(); 
            if($user->status == 0)
                return response()->json([
                                            'status'=>0,
                                            'base_url' => $this->base_url,
                                            'message'=>'Inactive User'
                                        ], $this->errorCode);
            $token =  $user->createToken('District10')->accessToken; 
            if(request('device_token') && request('device_type')) {                
                $dtUpdated = DeviceToken::updateOrCreate(
                        ['device_type' => request('device_type'), 'email_id' => request('email')],
                        ['device_token' => request('device_token')]
                    );
            }
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'Success',
                                        'token' => $token,
                                        'userDetail' => $user
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Unauthorised'
                                    ], $this->errorCode); 
        } 
    }
    /**
     * This function is used to logout user
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function logout(){ 
        if(request('email') && request('device_token') && request('device_type')) {                
            $dtDeleted = DeviceToken::where('email_id', request('email'))
                                    ->where('device_type', request('device_type'))
                                    ->where('device_token', request('device_token'))
                                    ->delete();
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'You have been successfully logged out'
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Unauthorised'
                                    ], $this->errorCode); 
        } 
    }    
    /**
     * This function is used to check user registration
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'company_name' => 'required',
            'email' => 'required|email|unique:users', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
            'chapter' => 'required', 
        ]);
        if ($validator->fails()) { 
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
               return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>$message
                                    ], $this->errorCode);
            }
        }
        $input = $request->all(); 
        $input['password'] = $password = Hash::make($input['password']);
        $input['rnumber'] = (isset($input['rnumber']))?$input['rnumber']:'';
        $input['company_name'] = (isset($input['company_name']))?$input['company_name']:'';
        $input['user_role'] = 3;
        $input['status'] = 0;
        $user = (new Users)->createUser($input);
        $success['name'] =  $user->name;
        return response()->json([
                                    'status'=>1,
                                    'base_url' => $this->base_url,
                                    'message'=>'User registered successfully. Wait for admin verification',
                                    'userDetail' => $user
                                ], $this->successCode); 
    }
    /**
     * This function is used to change user password
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function changePassword(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email', 
            'password' => 'required', 
            'new_password' => 'required',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
               return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>$message
                                    ], $this->errorCode);
            }
        }
        if (Auth::attempt(array('email' => $request->email, 'password' => $request->password))){
            $new_password = Hash::make($request->new_password);
            $updated = Users::where('email', $request->email)->update(['password' => $new_password]);
            if($updated) {
                return response()->json([
                                    'status'=>1,
                                    'base_url' => $this->base_url,
                                    'message'=>'Password updated successfully.'
                                ], $this->successCode); 
            } else {
                return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Wrong credentials.'
                                    ], $this->errorCode);
            }
        } else {
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Wrong credentials.'
                                    ], $this->errorCode);
        }
    }
    /**
     * This function is used to send forget email
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function forgetPassword(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
               return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>$message
                                    ], $this->errorCode);
            }
        }
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        if($response == Password::RESET_LINK_SENT) {
            return response()->json([
                                    'status'=>1,
                                    'base_url' => $this->base_url,
                                    'message'=>'Reset password email sent successfully.'
                                ], $this->successCode); 
        } else {
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Unable to send reset password email.'
                                    ], $this->errorCode);
        }
    }
    
    /**
     * This function is used to get user details
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function details(Request $request) 
    {
        return response()->json([
                                    'status'=>1,
                                    'base_url' => $this->base_url,
                                    'message'=>'User details',
                                    'data' => Auth::user()
                                ], $this->successCode);
    }
    /**
     * This function is used to update user settings
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function settingsPost(Request $request) 
    { 
        $id = Auth::id();
        $rules = [ 
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|email|unique:users,email,'.$id,
                    'primary_language' => 'required',
                    'font_size' => 'required',
                    'notification' => 'required',
                    'profile_pic' =>'image'                  
                ];
        if($request->password || $request->confirm_password){
            $rules['password'] = 'required';
            $rules['confirm_password'] = 'required|same:password';
        }
        $validator = Validator::make($request->all(),  $rules);
        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
               return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>$message
                                    ], $this->errorCode);
            }
        }
        $data = [
                    'name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'primary_language' => $request->primary_language,
                    'font_size' => $request->font_size,
                    'notification' => $request->notification
                ];
        if($request->password || $request->confirm_password)
            $data['password'] = Hash::make($request->password);

        if($request->phone)
            $data['phone'] = $request->phone;

        if($request->hasFile('profile_pic')) {
            $directory = public_path().'/users_pic';
            $imageName = strtotime(date('Y-m-d H:i:s')) . '-' . str_replace(' ', '-', $request->file('profile_pic')->getClientOriginalName());
            $request->file('profile_pic')->move($directory, $imageName);
            $data['profile_pic'] = 'users_pic/'.$imageName;
        }
        
        $updated = Users::where('id', Auth::id())->update($data);
        $info = Users::where('id', Auth::id())->first();
        $data['phone'] = $info->phone;
        $data['profile_pic'] = $info->profile_pic;
        
        if($updated) {
            unset($data['password']);
            return response()->json([
                                'status'=>1,
                                'base_url' => $this->base_url,
                                'message'=>'Settings updated successfully.',
                                'data' => $data,
                                'languages' => Language::All(),
                                'font_size' => $this->font_size
                            ], $this->successCode); 
        } else {
            return response()->json([
                                    'status'=>0,
                                    'base_url' => $this->base_url,
                                    'message'=>'Unable to update settings.'
                                ], $this->errorCode);
        }
    }
    /**
     * This function is used to get user settings
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function settingsGet(Request $request) 
    { 
        $user = Auth::user();
        $data = [
                    'first_name' => $user->name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'primary_language' => $user->primary_language,
                    'font_size' => $user->font_size,
                    'notification' => $user->notification,
                    'phone' => $user->phone,
                    'profile_pic' => $user->profile_pic,
                ];
        return response()->json([
                                    'status'=>1,
                                    'base_url' => $this->base_url,
                                    'message'=>'User Settings',
                                    'data' => $data,
                                    'languages' => Language::All(),
                                    'font_size' => $this->font_size
                                ], $this->successCode);
    }
    /**
     * This function is used to get chapter admin contact
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getChapterAdminContact(Request $request) 
    { 
        $data = [];
        if($request->chapter_id) {
            $data = (new Users)->where('chapter_id', $request->chapter_id)
                                ->where('user_role', 2)
                                ->get();
        } else {
            $data = (new Users)->where('user_role', 2)->get();
        }
        if($data && count($data)) {
            return response()->json([
                                'status'=>1,
                                'base_url' => $this->base_url,
                                'message'=>'Chapter admin contact details available',
                                'data' => $data,
                            ], $this->successCode);
        } else {
            return response()->json([
                                    'status'=>0,
                                    'base_url' => $this->base_url,
                                    'message'=>'Chapter admin contact details not available.',
                                    'data' => [],
                                ], $this->errorCode);
        }
    }    
}