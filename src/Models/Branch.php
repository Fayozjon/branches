<?php

namespace Botble\Branches\Models;

use Botble\Base\Models\BaseModel;

class Branch extends BaseModel
{
    protected $table = 'branches';

    protected $fillable = [
        'name',
        'logo',
        'image',
        'description',
        'history',
        'city',
        'district',
        'restaurant_type',
        'address',
        'phone',
        'email',
        'latitude',
        'longitude',
        'gallery',
        'status',
    ];

    protected $casts = [
        'gallery' => 'array',
    ];
}
