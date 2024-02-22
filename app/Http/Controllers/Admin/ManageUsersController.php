<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

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

    public function detail(int $id)
    {
        $user = User::findOrFail($id);

        $widget['total_deposit']     = Transaction::where('user_id', $user->id)->where('trx_type', 'Deposit')->count();
        $widget['total_withdrawals'] = Transaction::where('user_id', $user->id)->where('trx_type', 'Withdrawal')->count();
        $widget['total_exchanges'] = Transaction::where('user_id', $user->id)->where('trx_Type', 'Spot')->count();

        $widget['total_transaction'] = Transaction::where('user_id', $user->id)->count();

        $data = [
            'title' => ucwords($user->username) . "'s Details",
            'user' => $user,
            'widget' => $widget,
        ];

        return view('admin.users.details')->with($data);
    }
}
