<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'client_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'subtotal',
        'tax',
        'tax_amount',
        'discount',
        'discount_amount',
        'total',
        'total_no_tax',
        'total_with_discount',
        'currency',
        'language',
        'notes',
        'status',
        // Issuer (Company) details
        'issuer_name',
        'issuer_address',
        'issuer_city',
        'issuer_postal_code',
        'issuer_country',
        'issuer_phone',
        'issuer_email',
        'issuer_id_number',
        'issuer_tax_number',
        'issuer_authorized_person',
        'issuer_logo',
        // Client details
        'client_name',
        'client_address',
        'client_city',
        'client_postal_code',
        'client_country',
        'client_phone',
        'client_email',
        'client_id_number',
        'client_tax_number',
        'client_type',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'total_no_tax' => 'decimal:2',
        'total_with_discount' => 'decimal:2',
    ];

    protected $appends = ['issuer_logo_url'];

    public function getIssuerLogoUrlAttribute()
    {
        if (!$this->issuer_logo) {
            return null;
        }
        return asset('storage/' . $this->issuer_logo);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function specifications()
    {
        return $this->hasMany(InvoiceSpecification::class);
    }
}
