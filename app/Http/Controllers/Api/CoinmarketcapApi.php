<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class CoinmarketcapApi extends Controller
{
    private $key;

    public function __construct()
    {
        $this->key = "6567ef3d-f3e6-49f7-8d47-a17e0574d7f0";
    }

    public function fetchSymbolPriceUsd($symbol)
    {
        $url = "https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/latest?symbol=$symbol";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'X-CMC_PRO_API_KEY: ' . $this->key
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response =  json_decode($response, true);
        return $response['data'][$symbol][0]['quote']['USD']['price'];
    }
}
