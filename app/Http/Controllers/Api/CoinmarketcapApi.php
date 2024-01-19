<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoinmarketcapApi extends Controller
{
    private $key;

    public function __construct()
    {
        $this->key = "51df7514-244b-43fc-a90a-0f53482fc699";
    }
}
