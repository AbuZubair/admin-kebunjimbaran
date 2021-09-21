<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "product";

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
        'name',
        'harga_besar',
        'satuan_besar',
        'satuan_kecil',
        'harga_kecil',
        'point',
        'kategori',
        'path_photo',
        'harga_discount',
        'promo_id',
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
