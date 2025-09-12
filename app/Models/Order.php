<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'idempotency_key',
        'customer_name',
        'customer_phone',
        'processed_by',
        'subtotal',
        'tax',
        'discount',
        'total',
        'unique_code',
        'transfer_amount',
        'payment_method',
        'payment_type',
        'payment_status',
        'transfer_verified_at',
        'transfer_notes',
        'status',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getFormattedTransferAmountAttribute()
    {
        if ($this->transfer_amount) {
            return 'Rp ' . number_format($this->transfer_amount, 0, ',', '.');
        }
        return null;
    }

    public function getPaymentStatusLabelAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Transfer Terverifikasi',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            default => 'Unknown'
        };
    }

    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            'pending' => 'yellow',
            'verified' => 'blue',
            'paid' => 'green',
            'failed' => 'red',
            default => 'gray'
        };
    }
}
