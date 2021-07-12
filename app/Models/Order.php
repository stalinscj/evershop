<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    const STATUS_CREATED  = 'CREATED';
    const STATUS_PAYED    = 'PAYED';
    const STATUS_REJECTED = 'REJECTED';

    const STATUSES = [
        self::STATUS_CREATED,
        self::STATUS_PAYED,
        self::STATUS_REJECTED,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_mobile',
    ];

    /**
     * Get the last payment for the order.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function payment()
    {
        return $this->hasOne(OrderPayment::class)->latestOfMany();
    }

    /**
     * Get the payments for the order.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }

    /**
     * Set the order status.
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        if (in_array($status, self::STATUSES)) {
            $this->status = $status;

            $this->save();
        }

        return $this;
    }

    /**
     * Check if status order is "STATUS_CREATED" status.
     *
     * @return bool
     */
    public function isCreated()
    {
        return $this->status == self::STATUS_CREATED;
    }

    /**
     *  Alias for the "isCreated" method.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->isCreated();
    }

    /**
     * Check if status order is "STATUS_PAYED" status.
     *
     * @return bool
     */
    public function isPayed()
    {
        return $this->status == self::STATUS_PAYED;
    }

    /**
     * Check if status order is "STATUS_REJECTED" status.
     *
     * @return bool
     */
    public function isRejected()
    {
        return $this->status == self::STATUS_REJECTED;
    }
}
