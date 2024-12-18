<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'address'];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
