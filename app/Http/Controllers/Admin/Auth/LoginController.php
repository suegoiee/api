<?php

namespace App\Http\Controllers\Admin\Auth;

use Auth;
use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    protected $redirectTo = '/';
    public function __construct()
    {
       
    }
    public function loginForm()
    {
        return view('admin.auth.login');
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
            'name' => 'required',
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
        return $request->only('name', 'password');
    }

    protected function sendLoginResponse(Request $request)
    {
        //$request->session()->put('admin_name',$request->input('name'));
        //$request->session()->put('admin',true);
        $request->session()->regenerate();
        return redirect()->intended($this->redirectTo);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
       return redirect()->back()
            ->withInput($request->only('name'))
            ->withErrors([
                'name' => trans('auth.failed'),
            ]);
    }

    public function logout(Request $request)
    {
        //$request->session()->flush();
        $this->guard()->logout();

        $request->session()->regenerate();

        return redirect($this->redirectTo);
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }
}
