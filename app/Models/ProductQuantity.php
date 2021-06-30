<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductQuantity extends Model
{
    use HasFactory,SoftDeletes;
    public $fillable = ['quantity'];
    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Products::class);
    }
}
