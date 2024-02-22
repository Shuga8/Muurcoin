<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WithdrawalController extends Controller
{
    public function log(int $id)
    {
        $user = User::findOrFail($id);
        $withdrawals = Transaction::where('user_id', $user->id)->where('trx_type', 'Withdrawal')->orderBy('id', 'desc')->paginate(10);

        $data = [
            'title' => ucwords($user->username) . "'s withrawal log",
            'withdrawals' => $withdrawals
        ];
        return view('admin.withdrawal.details')->with($data);
    }
}
