<?php

namespace App\Http\Controllers;

use App\Models\User;
use Nette\Utils\Random;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreDepositRequest;

class DepositController extends Controller
{
    use HttpResponses;
    public function achieve(StoreDepositRequest $request)
    {
        $request->validated($request->all());

        $amount = (float) $request->amount;
        $symbol = strtoupper($request->symbol);

        $user = User::where('id', auth()->user()->id)->first();

        $balance = json_decode($user->balance, true);

        $balance = (array) $balance;

        try {

            DB::beginTransaction();

            $this->checkIfSymbolExists($symbol);

            $balance[$symbol] = (float) $balance[$symbol] + $amount;

            $user->balance = json_encode($balance);

            $transaction = [
                'user_id' => $user->id,
                'reference_id' => uniqid() . now() . Random::generate(10),
                'amount' => $amount,
                'wallet' => $symbol,
                'trx_type' => 'Deposit',
                'post_balance' => (float) $balance[$symbol],
                'details' => "$amount$symbol was Deposited",
                'status' => 'completed'
            ];

            \App\Models\Transaction::create($transaction);

            $user->save();

            DB::commit();

            return $this->success('', "Deposit of $amount into $symbol was successfull");
        } catch (\Throwable $th) {

            DB::rollBack();
            return $this->error('', $th->getMessage(), $th->getCode() ?: 406);
        }
    }

    public function checkIfSymbolExists($symbol)
    {
        $balance = Auth::user()->balance;

        $balance = (array) json_decode($balance);

        if (!array_key_exists($symbol, $balance)) {
            throw new \Exception("$symbol not acceptable", 406);
        } else {
            return true;
        }
    }
}
