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
        CoinsResource::collection(
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

        return $this->success([
            'response' => 'Request Validated'
        ]);

        try {

            DB::beginTransaction();
        } catch (\Throwable $th) {

            DB::rollBack();
            return $this->error('', throw $th, 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
