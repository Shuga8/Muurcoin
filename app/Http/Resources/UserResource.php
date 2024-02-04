<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'firstname' => (string) $this->firstname,
            'lastname' => (string) $this->lastname,
            'username' => (string) $this->username,
            'email' => (string) $this->email,
            'phone_number' => (string) $this->country_code . '' . $this->mobile,
            'balance' => $this->balance,
            'personal_coins_balance' => $this->personal_coins_balance,
            'wallet_address' => json_decode($this->wallet_address),
            'status' => (string) $this->status,
            'two_factor_activated' => (int) $this->wallet_factor_activated,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
