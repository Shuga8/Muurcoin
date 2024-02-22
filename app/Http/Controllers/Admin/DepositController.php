<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function details($id)
    {
        $user = User::findOrFail($id);
        $deposit = Transaction::where('id', $user->id)->where('trx_type', 'Deposit')->get();

        $data = [
            'title' => ucwords($user->username) . "'s deposit details",
            'details' => $deposit
        ];
        return view('admin.deposit.details')->with($data);
    }
}
