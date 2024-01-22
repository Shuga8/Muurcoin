<?php

namespace App\Http\Controllers;

use App\Models\Coin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CoinsResource;
use App\Http\Requests\StoreCoinRequest;

class CoinsController extends Controller
{

    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CoinsResource::collection(
            Coin::paginate(10)
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCoinRequest $request)
    {

        $request->validated($request->all());

        $data = [
            'name' => $request->name,
            'symbol' => $request->symbol,
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('coins', 'public');
        } else {
            return $this->error('', 'You must upload a image for the coin logo', 401);
        }

        $user = User::where('id', Auth::user()->id)->first();

        $personal_coins = $user->personal_coins_balance;

        try {

            DB::beginTransaction();


            if ($personal_coins == null || count((array) json_decode($personal_coins)) == 0 || $personal_coins == '{}') {
                $personal_coins = [];
                $personal_coins[$data['symbol']]  = 0;
                $personal_coins = json_encode($personal_coins);
                $user->personal_coins_balance = $personal_coins;
                $user->save();
            } else {

                $personal_coins = json_decode($personal_coins);

                $personal_coins = (array) $personal_coins;

                if (!array_key_exists($data['symbol'], $personal_coins)) {
                    $personal_coins[$data['symbol']] = 0;
                    $personal_coins = json_encode($personal_coins);
                    $user->personal_coins_balance = $personal_coins;
                    $user->save();
                }
            }


            Coin::create($data);

            DB::commit();
            return $this->success([
                'response' => $data['name'] . ' coin successfully created'
            ]);
        } catch (\Throwable $th) {

            DB::rollBack();
            return $this->error('', throw $th, 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $symbol)
    {

        return CoinsResource::collection(
            Coin::where('symbol', $symbol)->get()
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coin $coin)
    {
    }

    public function addCoinToPersonalCoinsBalance()
    {
    }
}
