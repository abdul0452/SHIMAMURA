<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'status',
        'total_amount',
    ];

    // Status yang dipakai sepanjang alur checkout & fulfilment
    public const STATUS_PENDING_PAYMENT = 'pending_payment';
    public const STATUS_PAID = 'paid';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Nama pembeli: ambil dari akun user kalau login, kalau tidak dari data guest
    public function getBuyerNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? '-';
    }

    public function getBuyerEmailAttribute(): ?string
    {
        return $this->user?->email ?? $this->guest_email;
    }
}
