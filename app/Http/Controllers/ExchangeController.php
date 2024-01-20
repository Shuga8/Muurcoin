<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreExchangeRequest;
use App\Http\Controllers\Api\CoinmarketcapApi as Api;

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

        /* Initialize API for coin market cap */
        $api = new Api();

        /* Coin equivalents */
        $fromSymbolUsdEquivalent = $api->fetchSymbolPriceUsd($fromSymbol);
        $toSymbolUsdEquivalent   = $api->fetchSymbolPriceUsd($toSymbol);

        /* User Balance */
        $user = Auth::user();

        $balance = json_decode($user->balance);

        /* If amount is greater than the amount present in the  user balance of the $fromSymol */

        if ($amount > (float) $balance->$fromSymbol) {
            return $this->error('', 'Insufficient Balance', 409);
        }
    }
}
