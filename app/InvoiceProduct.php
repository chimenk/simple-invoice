<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    protected $fillable = [
      'price', 'qty', 'total', 'name'
    ];

    public function invoice()
    {
      return $this->hasMany(Invoice::class);
    }
}
