<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        if (Auth::user()->isAdmin()) {
            return '/admin';
        }
        
        return '/login';
    }

    public function login(Request $request)
    {
        // validate the form data.
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        $email = $request->email;
        $password = $request->password;

        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //user sent their email 
            Auth::attempt(['email' => $email, 'password' => $password, 'role_id' => 1], $request->remember);
        } else {
            //they sent their username instead 
            Auth::attempt(['username' => $email, 'password' => $password, 'role_id' => 1], $request->remember);
        }

        // check if user is authenticated.
        if ( Auth::check() ) {
            //redirect to dashboard. 
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->back()->with('error', 'Username or password incorrect.')->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
