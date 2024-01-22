<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class CryptoController extends Controller
{

    use HttpResponses;

    public function index()
    {

        $balance = Auth::user()->balance;

        $balance = json_decode($balance);

        return $this->success([
            'currencies' => $balance
        ], Auth::user()->username . "'s crypto currencies");
    }
}
