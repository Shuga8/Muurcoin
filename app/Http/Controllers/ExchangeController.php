<?php

namespace App\Http\Controllers;

use App\Models\User;
use Nette\Utils\Random;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreExchangeRequest;
use App\Http\Controllers\Api\CoinmarketcapApi as Api;
use App\Models\Exchange;
use App\Models\Transaction;

class ExchangeController extends Controller
{

    use HttpResponses;

    public function store(StoreExchangeRequest $request)
    {

        $request->validated($request->all());

        $amount  = (float) $request->amount;
        $amount = abs($amount);
        $fromSymbol = $request->from;
        $toSymbol = $request->to;
        $type = $request->type;

        /* Check if symbols exists in the users balance */
        try {
            $this->checkIfSymbolExists($fromSymbol);
        } catch (\Throwable $th) {
            http_response_code(406);
            return $this->error('', $th->getMessage(), $th->getCode() ?: 406);
        }

        /* Check if symbols exists in the users balance */
        try {
            $this->checkIfSymbolExists($toSymbol);
        } catch (\Throwable $th) {
            return $this->error('', $th->getMessage(), $th->getCode() ?: 406);
        }

        /* Initialize API for coin market cap */
        $api = new Api();

        /* Coin equivalents */

        try {
            $fromSymbolUsdEquivalent = $api->fetchSymbolPriceUsd($fromSymbol);
            $toSymbolUsdEquivalent   = $api->fetchSymbolPriceUsd($toSymbol);
        } catch (\Throwable $th) {
            return $this->error('', $th->getMessage(), $th->getCode() ?: 406);
        }

        /* User */
        $user = User::where('id', Auth::user()->id)->first();

        /* decode balance from  json */
        $balance = json_decode($user->balance);

        /* If amount is greater than the amount present in the  user balance of the $fromSymol */

        if ($amount > (float) $balance->$fromSymbol) {
            return $this->error('', 'Insufficient Balance', 409);
        }

        /* Convert Amount from $fromSymbol to USD*/
        $usdEquivalentAmountOfExchangeAmountOfFromSymbol = $amount * (float) $fromSymbolUsdEquivalent;

        /* USD which was converted from $fromSymbol now converted to $toSymbol  */
        $toSymbolEquivalentOfExchangeAmount = $toSymbolUsdEquivalent * (float) $usdEquivalentAmountOfExchangeAmountOfFromSymbol;

        /* Change Balance Values */
        $balance->$fromSymbol  = (float) $balance->$fromSymbol - $amount;

        $balance->$toSymbol = (float) $balance->$toSymbol + $toSymbolEquivalentOfExchangeAmount;

        $newBalance = json_encode($balance);

        $ext = [
            'user_id' => $user->id,
            'amount' => (float) $amount,
            'from_wallet' => $fromSymbol,
            'to_wallet' => $toSymbol,
            'details' => "An Exchange of $amount$fromSymbol with $toSymbol"
        ];

        $transaction1 = [
            'user_id' => $user->id,
            'reference_id' => uniqid() . now() . Random::generate(10),
            'amount' => 0 - (float) $amount,
            'wallet' => $fromSymbol,
            'trx_type' => $type,
            'post_balance' => (float) $balance->$fromSymbol,
            'details' => "$amount$fromSymbol converted to $toSymbol",
            'status' => 'completed'
        ];

        $transaction2 = [
            'user_id' => $user->id,
            'reference_id' => uniqid() . now() . Random::generate(10),
            'amount' => +$toSymbolEquivalentOfExchangeAmount,
            'wallet' => $toSymbol,
            'trx_type' => $type,
            'post_balance' => (float) $balance->$toSymbol,
            'details' => "$toSymbolEquivalentOfExchangeAmount$toSymbol converted from $fromSymbol",
            'status' => 'completed'
        ];


        try {
            DB::beginTransaction();

            $user->update(['balance' => $newBalance]);

            Exchange::create($ext);

            Transaction::create($transaction1);

            Transaction::create($transaction2);

            DB::commit();

            return $this->success('', 'Exchange Successfull');
        } catch (\Throwable $th) {

            DB::rollBack();
            return $this->error('', $th->getMessage(), $th->getCode() ?: 410);
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
