<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories';
    
    protected $fillable = ['id', 'category'];
    
    
            
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'category_id');
    }
            

}
    

