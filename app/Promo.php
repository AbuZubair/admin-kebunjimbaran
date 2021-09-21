<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = "promo";

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
        'promo_name',
        'kode_promo',
        'discount',
        'discount_amount',
        'path_photo',
        'is_active'
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
