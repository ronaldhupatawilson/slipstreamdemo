<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;


class CategoryResource extends JsonResource
{
    /**
     * Preserve the collection keys.
     *
     * @var bool
     */
    public $preserveKeys = true;
    
    /**
     * removes envelope from passed in data
     */
    public static $wrap = false;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category,
            //'created_at' => (new Carbon($this->created_at))->format('Y-m-d H:i:s.u'),
            //'updated_at' => (new Carbon($this->updated_at))->format('Y-m-d H:i:s.u'),
           
            'customers' => new CustomerResource($this->customer),

        ];
    }
}
    