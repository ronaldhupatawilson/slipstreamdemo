<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;


class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'reference' => $this->reference,
            'category_id' => $this->category_id,
            'startDate' => (new Carbon($this->startDate))->format('Y-m-d'),
            'description' => $this->description,
            //'created_at' => (new Carbon($this->created_at))->format('Y-m-d H:i:s.u'),
            //'updated_at' => (new Carbon($this->updated_at))->format('Y-m-d H:i:s.u'),
           
            'categories' => new CategoryResource($this->category),
            'categoriescategory' => $this->categoriescategory,
           
            'contacts' => new ContactResource($this->contact),

        ];
    }
}
    