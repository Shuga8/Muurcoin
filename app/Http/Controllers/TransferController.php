<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransferRequest;

class TransferController extends Controller
{
    use HttpResponses;
    public function achieve(TransferRequest $request)
    {
        $request->validated($request->all());

        if (strtolower(auth()->user()->username) == (strtolower(trim($request->username)))) {
            return $this->error(null, "You cannot transfer to yourself", 406);
        } else {
            return $this->success(null, 'continue');
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
