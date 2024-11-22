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
            "id" => $this->id,
            "type" => $this->type,
            "method" => $this->method,
            "description" => $this->description,
            "frequency" => $this->frequency,
            "interval" => $this->interval,
            "transaction_date" => $this->transaction_date,
            "amount" => (float) $this->amount,
            'installment' => $this->installment,
//            "start_date" => $this->start_date,
//            "end_date" => $this->end_date,
            "source" => $this->resolveSource(),
            "status" => $this->status,
            "category" => CategoryResource::make($this->whenLoaded('category')),
            "user" => UserResource::make($this->whenLoaded('user')),
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }

    protected function resolveSource()
    {
        if ($this->source_type === 'account' && $this->relationLoaded('sourceAccount')) {
            return new SourceResource($this->sourceAccount);
        } elseif ($this->source_type === 'credit_card' && $this->relationLoaded('sourceCreditCard')) {
            return new SourceResource($this->sourceCreditCard);
        }

        return null;
    }
}
