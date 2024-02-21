<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function index()
    {

        $widget['total_users'] = User::count();
        $data = [
            'title' => 'Dashboard',
            'widget' => $widget,
        ];

        return view('admin.dashboard')->with($data);
    }
}
