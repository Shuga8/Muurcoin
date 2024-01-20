<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExchangeRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    use HttpResponses;
    public function store(StoreExchangeRequest $request)
    {
        $request->validated($request->all());

        $fromSymbol = $request->from;
        $toSymbol = $request->to;

        return $this->success('', 'Continue');
    }
}
