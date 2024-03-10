<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;

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

    public function quests(int $id)
    {
        $user = User::findOrFail($id);
        $withdrawals = WithdrawalRequest::where('user_id', $id)->latest()->paginate(10);

        $data = [
            'title' => 'Withrawal Requests',
            'withdrawals' => $withdrawals,
            'user' => $user,
        ];

        return view('admin.withdrawal.requests')->with($data);
    }

    public function update(Request $request, int $id)
    {
        $withdrawal = WithdrawalRequest::findOrFail($id);

        $withdrawal->status = $request->status;

        $withdrawal->save();

        $notify[] = ['success', 'status updated successfully'];

        return back()->withNotify($notify);
    }
}
