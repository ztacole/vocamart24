<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = ['transaction_header_id', 'product_id', 'quantity'];

    public $timestamps = false;

    public function transactionHeader() {
        return $this->belongsTo(TransactionHeader::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
