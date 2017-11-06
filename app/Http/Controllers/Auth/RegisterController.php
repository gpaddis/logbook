<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
    protected $redirectTo = '/';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Show the admin registration form.
     *
     * @return Illuminate\Http\Response
     */
    public function showAdminRegistrationForm()
    {
        $this->checkIfAdminExists();

        return view('auth.register-admin');
    }

    /**
     * Handle an admin registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerAdmin(Request $request)
    {
        $this->checkIfAdminExists();
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->createAdmin($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
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
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * Create a new admin instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function createAdmin(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ])->assignRole('admin');
    }

    /**
     * Check if an admin is already present in the database and throw an exception
     * if this is the case.
     *
     * @throws Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    protected function checkIfAdminExists()
    {
        $role = \Spatie\Permission\Models\Role::findByName('admin');

        if ($role->users->isNotEmpty()) {
            abort(403, 'An admin is already present in the database.');
        }
    }
}
