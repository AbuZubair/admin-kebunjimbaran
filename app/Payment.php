<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = "payment";

    public $primaryKey = "id";

    const CREATED_AT = 'created_date';

    const UPDATED_AT = 'updated_date';

    public $incrementing = true;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'order_no',
        'invoice_no',
        'charge_amount',
        'payment_type',
        'bank',
        'payment_status',
        'paid_at',
        'paid_amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'created_date', 'updated_date'
    ];

}
