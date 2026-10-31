<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_subtotal', 'max_uses',
        'times_used', 'starts_at', 'expires_at', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'integer',
            'min_subtotal' => 'integer',
            'max_uses' => 'integer',
            'times_used' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function setCodeAttribute(string $value): void
    {
        $this->attributes['code'] = strtoupper(trim($value));
    }

    /** Restituisce null se valido, altrimenti il motivo per cui non è applicabile. */
    public function invalidReason(int $subtotal): ?string
    {
        return match (true) {
            ! $this->is_active => 'Il coupon non è attivo.',
            $this->starts_at && $this->starts_at->isFuture() => 'Il coupon non è ancora valido.',
            $this->expires_at && $this->expires_at->isPast() => 'Il coupon è scaduto.',
            $this->max_uses !== null && $this->times_used >= $this->max_uses => 'Il coupon ha raggiunto il limite di utilizzi.',
            $subtotal < $this->min_subtotal => 'Il carrello non raggiunge l\'importo minimo per questo coupon.',
            default => null,
        };
    }

    public function discountFor(int $subtotal): int
    {
        if ($this->invalidReason($subtotal) !== null) {
            return 0;
        }

        $discount = $this->type === 'percent'
            ? (int) round($subtotal * min($this->value, 100) / 100)
            : $this->value;

        return min($discount, $subtotal);
    }
}
