<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WithdrawalRequest;
use App\Models\WithdrawalRequest as Withdrawal;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;

class WithdrawalRequestsController extends Controller
{

    use HttpResponses;

    public function index()
    {

        $withdrawals = Withdrawal::where('user_id', auth()->user()->id)->latest()->paginate();

        return $this->success($withdrawals);
    }
    public function place(WithdrawalRequest $request)
    {

        $data = [
            'user_id' => auth()->user()->id,
            'wallet' => strtoupper($request->wallet),
            'amount' => (float) $request->amount,
            'acc_number' => $request->acc_number,
            'acc_name' => $request->acc_name,
            'bank_name' => $request->bank_name,
        ];


        try {
            $this->checkIfSymbolExists($data['wallet']);
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), 406); // Check if the code is an integer
        }

        $user = auth()->user();

        $balance = json_decode($user->balance, true);

        if ($data['amount'] > $balance[$data['wallet']]) {
            return $this->error(null, 'Insufficient balance.', 406);
        }
        try {
            DB::beginTransaction();
            Withdrawal::create($data);
            DB::commit();
            return $this->success(null, 'Withdrawal successfully placed');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(null, $e->getMessage(), 406); // Check if the code is an integer
        }
    }

    public function checkIfSymbolExists($symbol)
    {
        $balance = Auth::user()->balance;
        $balance = json_decode($balance, true);

        if (!array_key_exists($symbol, $balance)) {
            throw new \Exception("$symbol not acceptable", 406);
        } else {
            return true;
        }
    }
}
