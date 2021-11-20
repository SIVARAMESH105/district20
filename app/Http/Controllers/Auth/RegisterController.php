<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Models\Chapter;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * This function is used to  display the chapter in the selectbox section
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Techaffinity:sivaramesh
     */
        public function showRegistrationForm()
        {
            $data['chapters'] = Chapter::all();     
            return view('auth.register', $data);
        }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = array(
        'name.required' => 'Please enter a name',
        'company_name.required' => 'Please enter a company name',
        'email.required' => 'Please enter a email address',
        'password.required' => 'Please enter a password',
        'password_confirmation.required' => 'Please enter a confirm password',
        'chapter.required' => 'Please select a chapter',
        'registration_number.required' => 'Please enter a register number'
        );
        return Validator::make($data,[
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'chapter' => ['required'],
            'password_confirmation' => ['required','same:password'], 
            'registration_number' => ['required'],
        ],$message);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'company_name'=> $data['company_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'chapter_id' => $data['chapter'],
            'registration_number' => $data['registration_number'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);

        $request->session()->flash('success', "New User Registered Successfully.");
        return redirect(route('register'));
    }
}
