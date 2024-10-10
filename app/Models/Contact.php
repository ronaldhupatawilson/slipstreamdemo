<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Contact extends Model
{
    use HasFactory;
    
    protected $table = 'contacts';
    
    protected $fillable = ['id', 'customer_id', 'firstName', 'lastName'];
    
    
            
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
            

}
    

