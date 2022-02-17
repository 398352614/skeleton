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
 class Route extends Model
 {
     protected $table = 'route';

     protected $fillable = [
         'user_id',
         'route_number',
         'distance',
         'time',
         'arrive_time',
         'sort_times',
     ];

     protected $hidden = [
     ];

 }
