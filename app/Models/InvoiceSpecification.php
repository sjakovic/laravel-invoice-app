<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceSpecification extends Model
{
    protected $fillable = [
        'invoice_id',
        'date',
        'description',
        'timespent',
        'price_per_hour',
        'total'
    ];

    protected $casts = [
        'date' => 'date',
        'timespent' => 'integer',
        'price_per_hour' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
} 