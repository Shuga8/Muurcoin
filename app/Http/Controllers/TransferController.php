<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    use HttpResponses;
    public function achieve(TransferRequest $request)
    {
    }
}
