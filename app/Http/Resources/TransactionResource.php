<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'reference_id' => (string) $this->reference_id,
            'amount' => (float) $this->amount,
            'charge' => (float) $this->charge,
            'wallet' => (string) $this->wallet,
            'trx_type' => (string) $this->trx_type,
            'post_balance' => (float) $this->post_balance,
            'details' => (string) $this->details,
            'status' => (string) $this->status
        ];
    }
}
