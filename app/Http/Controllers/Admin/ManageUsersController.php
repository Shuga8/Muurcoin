<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManageUsersController extends Controller
{

    public function allUsers()
    {

        $data = [
            'title' => 'All Users',
            'users' => $this->userData()
        ];
        return view('admin.users.list')->with($data);
    }

    public function activeUsers()
    {

        $data = [
            'title' => 'All Users',
            'users' => $this->userData('active'),
        ];
        return view('admin.users.list')->with($data);
    }

    protected function userData($scope = null)
    {
        if ($scope) {
            $users = User::$scope();
        } else {
            $users = User::query();
        }
        return $users->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function bannedUsers()
    {
        $data = [
            'title' => 'Banned Users',
            'users' => $this->userData('banned')
        ];

        return view('admin.users.list')->with($data);
    }

    public function unverifiedEmailUsers()
    {
        $data = [
            'title' => 'Unverified Email Users',
            'users' => $this->userData('emailUnverified')
        ];

        return view('admin.users.list')->with($data);
    }

    public function verifiedEmailUsers()
    {

        $data = [
            'title' => 'Verified Email Users',
            'users' => $this->userData('emailVerified')
        ];

        return view('admin.users.list')->with($data);
    }
}
