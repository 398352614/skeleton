<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'source';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $hidden = [];
    protected $dates = [];

    protected $fillable = [
        'company_id',

        'source_name',

        'created_at',
        'updated_at',
        ];
}
