<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'vocational_id', 'image', 'price', 'stock'];

    public function vocational() {
        return $this->belongsTo(Vocational::class);
    }

    public function transactionDetails() {
        return $this->hasMany(TransactionDetail::class);
    }
}

