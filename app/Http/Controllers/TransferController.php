<?php

namespace App\Http\Controllers;

use App\Models\User;
use Nette\Utils\Random;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransferRequest;
use App\Models\Transaction;

class TransferController extends Controller
{
    use HttpResponses;
    public function achieve(TransferRequest $request)
    {
        $request->validated($request->all());

        if (strtolower(auth()->user()->username) == (strtolower(trim($request->username)))) {
            return $this->error(null, "You cannot transfer to yourself", 406);
        }

        try {
            $this->checkIfSymbolExists(strtoupper($request->wallet));
        } catch (\Throwable $th) {
            return $this->error(null, $th->getMessage(), $th->getCode()  ?: 406);
        }

        $sender = User::where('id', auth()->user()->id)->first();

        $senderBalance  = json_decode($sender->balance, true);

        $recipient = User::where('username', $request->username)->first();

        try {
            $this->checkIfSymbolExistsForRecipient(strtoupper($request->wallet), $recipient);
        } catch (\Throwable $th) {
            return $this->error(null, $th->getMessage(), $th->getCode()  ?: 406);
        }

        $recipientBalance  = json_decode($recipient->balance, true);


        $amount = abs($request->amount);


        if ($amount > $senderBalance[$request->wallet]) {
            return $this->error(null, "Amount cannot be less than available balance for $request->wallet", 406);
        }
        // Update balances
        $senderBalance[$request->wallet] -= $amount;
        $recipientBalance[$request->wallet] += $amount;

        try {

            DB::beginTransaction();
            $transaction1 = [
                'user_id' => $sender->id,
                'reference_id' => uniqid() . now() . Random::generate(10),
                'amount' => -$amount,
                'wallet' => $request->wallet,
                'trx_type' => 'Transfer',
                'post_balance' => (float) $senderBalance[$request->wallet],
                'details' => "$amount$request->wallet was transfered to $request->username",
                'status' => 'completed'
            ];

            $transaction2 = [
                'user_id' => $recipient->id,
                'reference_id' => uniqid() . now() . Random::generate(10),
                'amount' => $amount,
                'wallet' => $request->wallet,
                'trx_type' => 'Transfer',
                'post_balance' => (float) $recipientBalance[$request->wallet],
                'details' => "$amount$request->wallet was recieved from $sender->username",
                'status' => 'completed'
            ];

            Transaction::create($transaction1);
            Transaction::create($transaction2);

            $senderBalance  = json_encode($senderBalance);
            $recipientBalance  = json_encode($recipientBalance);

            $sender->update(['balance' => $senderBalance]);
            $recipient->update(['balance' => $recipientBalance]);

            DB::commit();
            return $this->success(null, "Transfer of $amount$request->wallet to $request->username  was successfull");
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->error('', $e->getMessage(), $e->getCode() ?: 417);
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

    public function checkIfSymbolExistsForRecipient($symbol, $recipient)
    {
        $balance =  json_decode($recipient->balance, true);

        if (!array_key_exists($symbol, $balance)) {
            throw new \Exception("Recipient cannot access $symbol", 406);
        } else {
            return true;
        }
    }
}
