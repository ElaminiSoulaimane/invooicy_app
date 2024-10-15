<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Invoice extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'client_id',
    //     'user_id',
    //     'total_amount',
    //     'remaining_balance',
    //     'status',
    //     'invoice_number',
    //     'discount_amount',
    //     'discounted_total',
    // ];
    protected $fillable = [
        'client_id',
        'user_id',
        'status',
        'invoice_number',
        'total_amount',
        'discounted_total',
        'remaining_balance',
        'discount_amount',
    ];
    public function getDiscountedTotalAttribute()
    {
        return max(0, $this->total_amount - $this->discount_amount);
    }

    public function getRemainingBalanceAttribute()
    {
        return max(0, $this->discounted_total - $this->payments->sum('amount'));
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
