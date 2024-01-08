<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'username', 'password');
        $credentials['email'] = $credentials['email'] ?? $credentials['username'];
        unset($credentials['username']);
//        dd($credentials);
        if (Auth::attempt($credentials)) {
            // Authentication passed
            if (auth()->user()->user_role_id == $request->input('user_role_id')) {
                if ($request->ajax()) {
                    return response()->json(['success' => true, 'message' => 'Login successful']);
                } else {
                    return redirect()->intended('admin/dashboard');
                }
            }
            Auth::logout();
        }

        // Authentication failed
        $errorMessage = 'Invalid credentials';

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $errorMessage], 401);
        } else {
            return back()->withErrors(['email' => $errorMessage]);
        }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }
}
