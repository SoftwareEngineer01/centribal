<?php

namespace App\Http\Resources;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'price' => $this->price,
            'date_order' => $this->date_order,
            'date_delivery' => $this->date_delivery,
            'customer' => new CustomerResource($this->customer),
            'articles' => ArticleResource::collection($this->articles),
        ];
    }
}
