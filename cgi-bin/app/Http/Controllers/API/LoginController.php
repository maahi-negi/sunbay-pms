<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use App\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Admins;
use Validator;
use App\Validators\UsersValidator;
use DB;
use App\Mail\SendMail;

class LoginController extends Controller
{
    use HelperTrait, UsersValidator;

    public function index(Request $request)
    {
        if (isset(Auth::user()->id)) {
            return ['status' => 'success1'];
        }

        // check for email if exist

        $user = Admins::where('email', $request['email'])->get()->first();
        if(!$user):
            return ['status' => 'failed', 'message' => 'Your email does not exist. Please register to continue.'];
        elseif($user->status_id != 2):
            return ['status' => 'failed', 'message' => 'You account has been deactivated. Please contact administrator.'];
        elseif(Hash::check($request['password'],$user->password)):
                Auth::loginUsingId($user->id);
            return ['status' => 'success', 'res' => Auth::loginUsingId($user->id), $user];
        else:
            return ['status' => 'failed', 'message' =>  'Invalid login credentials. Please try again.'];
        endif;

    }

    public function register(Request $request)
    {
        $user = Admins::where('email', $request['email'])->get()->first();
        if($user):
            return ['status' => 'failed', 'message' => 'Your email already exist. Please login or use forgot password.'];
        else:
            $user  = Admins::Create(['email' => $request['email'],
                                    'name' => $request['name'],
                                    'admin_role_id' => 2,
                                    'mobile' => $request['mobile'],
                                    'password' => Hash::make($request['password'])]);
            Auth::login($user);
            return ['status' => 'success'];
        endif;
    }

    public function forgotPassword(Request $request)
    {
       // return $request;

        $user = Admins::where('email', $request['email'])->get()->first();
        if(!$user):
            return ['status' => 'failed', 'message' => 'Your email does not exist. Please register to continue.'];
        elseif($user->status_id != 2):
            return ['status' => 'failed', 'message' => 'You account has been deactivated. Please contact administrator.'];
        else:
            $code = rand(1000000, 9999999);
            Admins::where('id', $user->id)->update(['vcode' => $code]);

            $data = ['subject'=> 'XPlat - Reset Your Login Password',
                        'view' => 'forgot_password_email',
                        'code' => $code,
                        'name' => $user->name];
            Mail::to($user->email)->send(new SendMail($data));

            return ['status' => 'success', 'message' =>  'Your verification code has been sent on your email. Please verify your email.'];
        endif;

    }

    public function verifyCode(Request $request)
    {
       // return $request;

        $user = Admins::where(['email' => $request['email']])->get()->first();
        if(!$user):
            return ['status' => 'failed', 'message' => 'Your email does not exist. Please register to continue.'];
        elseif($user->status_id != 2):
            return ['status' => 'failed', 'message' => 'You account has been deactivated. Please contact administrator.'];
        else:
            $ucheck = Admins::where(['email' => $request['email'], 'vcode' => $request['vcode']])->get()->first();
            if($ucheck) {return ['status' => 'success', 'message' => 'Your email has been verified. Please update your password now.']; }
            else { return ['status' => 'failed', 'message' => 'Incorrect Verification Code. Please try again.']; }
        endif;

    }

    public function forgotPasswordAdmin(Request $request)
    {
       // return $request;
        $user = Admins::where('email', $request['email'])->where('admin_role_id',1)->get()->first();
        if(!$user):
            return redirect()->back()->withErrors(['email' => 'Please fill the registered email']);
        else:
            $code = rand(1000000, 9999999);
            Admins::where('id', $user->id)->update(['vcode' => $code]);

            $data = ['subject'=> 'XFloor - Reset Your Login Password',
                        'view' => 'forgot_password_email',
                        'code' => $code,
                        'name' => $user->name];
            Mail::to($user->email)->send(new SendMail($data));

            return Redirect()->Route('reset-password');

        endif;

    }

    public function updatePassword(Request $request)
    {
       // return $request;

        $user = Admins::where(['email' => $request['email'], 'vcode' => $request['vcode']])->get()->first();
        if(!$user):
            return ['status' => 'failed', 'message' => 'Your Details can not be updated. Please do the process properly or contact administrator.'];
        else:
            $code = $request['passcode'];
            Admins::where('id', $user->id)->update(['password' => Hash::make($code), 'vcode' => '']);
            // Auth::login($user);
            return ['status' => 'success', 'message' => "Your password has been changed successfully"];
        endif;
    }

    public function updatePasswordAdmin(Request $request)
    {
        $user = Admins::where(['email' => $request['email']])->where('admin_role_id',1)->get()->first();
        if(!$user):
            return redirect()->back()->withErrors(['email' => 'Please fill the registered email']);

        else:
            $ucheck = Admins::where(['email' => $request['email'], 'vcode' => $request['otp']])->get()->first();
            if($ucheck)
                {
                    if($request['password']==$request['password_confirmation'])
                    {
                        $code = $request['password'];
                        Admins::where('id', $user->id)->update(['password' => Hash::make($code), 'vcode' => '']);
                        return Redirect()->Route('admin-login')->with('status', 'Your password has been changed successfully');
                    }
                    else
                    {
                        return redirect()->back()->withErrors(['password' => 'Password and confirm password does not match']);
                    }

                }
            else
                {
                    return redirect()->back()->withErrors(['otp' => 'Incorrect Verification Code. Please try again.']);
                }
        endif;

    }

    public function check()
    {
        if (isset(Auth::user()->id))
        {
            return ['status' => 'success'];
        }
        else {
            return ['status' => 'fail'];
        }
    }

        public function google_login(Request $request){
        $user = Admins::where('google_id', $request->g_id)->get()->first();
        if(!$user):
            $user_email = Admins::where('id', $request->email)->get()->first();
            if(!$user_email):
            $create_user  = Admins::Create([
                                    'email'         => $request->email,
                                    'name'          => $request->name,
                                    'admin_role_id' => 2,
                                    'google_id'     => $request->g_id
                                    ]);

            else:
                $create_user = Admins::where('email', $request->email)->update('google_id', $request->g_id);
            endif;
                Auth::login($create_user);
                return ['status' => 'success'];
        else:
            Auth::loginUsingId($user->id);
            return ['status' => 'success', 'res' => Auth::loginUsingId($user->id), $user];
        endif;
    }

       public function fb_login(Request $request){
        $user = Admins::where('fb_id', $request->fb_id)->get()->first();
        if(!$user):
            $user_email = Admins::where('id', $request->email)->get()->first();
            if(!$user_email):
            $user  = Admins::Create([
                                    'email'         => $request->email,
                                    'name'          => $request->name,
                                    'admin_role_id' => 2,
                                    'fb_id'     => $request->fb_id
                                    ]);
            else:
                $create_user = Admins::where('email', $request->email)->update('fb_id', $request->fb_id);
            endif;
            Auth::login($user);
            return ['status' => 'success'];
        else:
            Auth::loginUsingId($user->id);
            return ['status' => 'success', 'res' => Auth::loginUsingId($user->id), $user];
        endif;
    }

}
