<?php

namespace App\Http\Controllers\Admin\Auth;

use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AdminRepository;

class LoginController extends Controller
{
    protected $admin;
    protected $redirectTo = '/';
    public function __construct(AdminRepository $admin)
    {
        $this->admin = $admin;
    }
    public function loginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $this->credentials($request);

        if ($this->admin->check($credentials)) {

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
    protected function credentials(Request $request)
    {
        return $request->only('name', 'password');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->put('admin_name',$request->input('name'));
        $request->session()->put('admin',true);
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
        $request->session()->flush();

        $request->session()->regenerate();

        return redirect($this->redirectTo);
    }
}
