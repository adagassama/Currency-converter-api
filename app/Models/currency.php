<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class currency extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['is_default' => 'boolean'];

    public function fromExchangeRates()
    {
        return $this->hasMany(ExchangeRate::class, 'from_id');
    }

    public function toExchangeRates()
    {
        return $this->hasMany(ExchangeRate::class, 'to_id');
    }

    public function countries()
    {
        return $this->hasMany(Country::class, 'currency_code', 'code');
    }

    public function scopeDefault($query)
    {
        return $query->whereIsDefault(true);
    }

    public function scopeForeign($query)
    {
        return $query->whereIsDefault(false);
    }

    protected static function booted()
    {
        static::creating(fn ($currency) => $currency->fillDefault());
        static::deleting(fn ($currency) => $currency->checkDefault());
    }

    private function fillDefault()
    {
        $this->is_default = ! self::query()->default()->exists();
    }

    private function checkDefault()
    {
        if ($this->is_default && self::count() > 1) {
            throw Exception::cannotDeleteDefault();
        }
    }
}