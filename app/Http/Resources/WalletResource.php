<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'min_balance' => $this->min_balance,
            'monthly_interest_rate' => $this->monthly_interest_rate,
            'wallet_created_at' => $this->created_at,
            'wallet_updated_at' => $this->updated_at
        ];
    }
}
