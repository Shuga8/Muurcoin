<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DepositController extends Controller
{

    public function index()
    {
        $transactions = Transaction::where('trx_type', 'Deposit')->paginate(getPaginate());

        $data = [
            'title' => 'All Deposits',
            'transactions' => $transactions
        ];

        return view('admin.deposit.all')->with($data);
    }

    public function details($id)
    {
        $user = User::findOrFail($id);
        $deposit = Transaction::where('user_id', $user->id)->where('trx_type', 'Deposit')->orderBy('id', 'desc')->paginate(10);

        $data = [
            'title' => ucwords($user->username) . "'s deposit details",
            'deposits' => $deposit
        ];
        return view('admin.deposit.details')->with($data);
    }
}
