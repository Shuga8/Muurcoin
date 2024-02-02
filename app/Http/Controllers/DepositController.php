<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepositRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    use HttpResponses;
    public function achieve(StoreDepositRequest $request)
    {
        $request->validated($request->all());

        $amount = (float) $request->amount;
        $symbol = strtoupper($request->symbol);

        return $this->success([
            'symbol' => $symbol,
            'amount' => (float) number_format((float)$amount, 2),
        ], 'Continue');
    }
}
