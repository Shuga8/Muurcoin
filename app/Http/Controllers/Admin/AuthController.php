<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Admin Login'
        ];

        return view('admin.auth.login')->with($data);
    }

    public function auth(AdminLoginRequest $request)
    {
        $request->validated();

        if (auth('admin')->attempt(['username' => $request->username, 'password' => $request->password])) {

            $request->session()->regenerate();

            $notify[] = ['success', 'You are now logged in'];

            return redirect(route('admin.dashboard'))->withNotify($notify);
        }

        $notify[] = ['error', 'Invalid Credentials'];
        return back()->withNotify($notify);
    }
}
