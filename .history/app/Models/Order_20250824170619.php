<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Order Model
 * 
 * Represents customer orders in the e-commerce system
 * Includes status tracking and total amount calculation
 */
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for this order
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Check if order is in a specific status
     *
     * @param string $status
     * @return bool
     */
    public function hasStatus($status)
    {
        return $this->status === $status;
    }

    /**
     * Check if order is completed
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === 'delivered';
    }

    /**
     * Check if order is cancelled
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
