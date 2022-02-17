<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Model;


/**
 * Class Route.
 */
class Station extends Model
{
    protected $table = 'station';

    protected $fillable = [
        'user_id',
        'station_number',
        'route_number',

        'serial_number',
        'distance',
        'time',
        'arrive_time',
        'status',
    ];

    protected $hidden = [
    ];

}
