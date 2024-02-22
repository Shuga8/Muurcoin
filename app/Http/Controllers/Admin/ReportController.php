<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function transaction(int $id)
    {
        $user = User::findOrFail($id);
        $transactions = Transaction::where('user_id', $user->id)->paginate(10);

        $data = [
            'title' => ucwords($user->username) . "'s transaction report",
            'transactions' => $transactions
        ];
        return view('admin.transaction.details')->with($data);
    }

    public function all()
    {

        $transactions = Transaction::paginate(getPaginate());

        $data = [
            'title' => 'All Transactions',
            'transactions' => $transactions
        ];

        return view('admin.transaction.all')->with($data);
    }
}
