<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    protected $table = "tmp_global_reference";

    public $primaryKey = "id";

    public $incrementing = true;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'ref_value',
        'ref_label',
        'param',
        'flag',
        'is_active'
    ];

}
