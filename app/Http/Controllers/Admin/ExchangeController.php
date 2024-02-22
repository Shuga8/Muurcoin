<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exchange;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function index()
    {

        $exchanges = Exchange::paginate(getPaginate());

        $data = [
            'title' => 'All Exchanges',
            'exchanges' => $exchanges
        ];

        return view('admin.exchanges.all', $data);
    }
}
