<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order";

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
        'transaction_date',
        'total_amount',
        'subtotal_amount',
        'fullname',
        'email',
        'address',
        'phone',
        'status_kirim',
        'delivery_date',
        'promo_id'
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
