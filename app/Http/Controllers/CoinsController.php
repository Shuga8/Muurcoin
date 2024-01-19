<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoinRequest;
use App\Models\Coin;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CoinsResource;

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

        return $this->error('', 'Unauthorized', 403);

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

        try {

            DB::beginTransaction();

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
}
