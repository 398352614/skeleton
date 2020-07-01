<?php

namespace App\Models;

use Jiaxincui\ClosureTable\Traits\ClosureTable;

class Institution extends BaseModel
{
    use ClosureTable;

    protected $guarded = [];

    protected $table = 'institutions';

    protected $closureTable = 'institutions_closure';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = [
        'name',
        'phone',
        'contacts',
        'country',
        'address',
        'company_id',
        'parent',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    protected $appends =[
        'country_name'
    ];
}
