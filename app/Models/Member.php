<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'is_active',
        'points'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'points' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($member) {
            if (empty($member->member_id)) {
                $member->member_id = 'MEM' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the member's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'member_id', 'member_id');
    }

    /**
     * Scope a query to only include active members
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive members
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Add points to member
     */
    public function addPoints($amount)
    {
        $this->points += $amount;
        $this->save();
        return $this;
    }

    /**
     * Deduct points from member
     */
    public function deductPoints($amount)
    {
        if ($this->points >= $amount) {
            $this->points -= $amount;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Check if member has enough points
     */
    public function hasEnoughPoints($amount)
    {
        return $this->points >= $amount;
    }

    /**
     * Calculate points earned from order amount (2%)
     */
    public static function calculatePointsEarned($orderAmount)
    {
        return floor($orderAmount * 0.02);
    }
}
