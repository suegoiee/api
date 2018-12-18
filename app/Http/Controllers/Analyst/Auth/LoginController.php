<?php

namespace App\Http\Controllers\Analyst\Auth;

use Auth;
use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    protected $redirectTo = '/analyst';
    public function __construct()
    {

    }
    public function loginForm()
    {
        if(Auth::guard('analyst')->check()){
            return redirect()->route('analyst.home');
        }
        return view('analyst.auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        return $this->sendFailedLoginResponse($request);
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);
    }
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }
    protected function credentials(Request $request)
    {
        return $request->only('email', 'password');
    }

    protected function sendLoginResponse(Request $request)
    {
        //$request->session()->put('admin_name',$request->input('name'));
        //$request->session()->put('admin',true);
        //$request->session()->regenerate();
        return redirect()->intended($this->redirectTo);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
       return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => trans('auth.failed'),
            ]);
    }

    public function logout(Request $request)
    {
        //$request->session()->flush();
        $this->guard()->logout();

        //$request->session()->regenerate();

        return redirect($this->redirectTo);
    }

    protected function guard()
    {
        return Auth::guard('analyst');
    }
}
