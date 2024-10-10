<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Customer extends Model
{
    use HasFactory;
    
    protected $table = 'customers';
    
    protected $fillable = ['id', 'name', 'reference', 'category_id', 'startDate', 'description'];
    
    
            
    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'customer_id');
    }
            
            
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
            

}
    

