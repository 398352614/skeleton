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

 use Qbhy\HyperfAuth\Authenticatable;

 /**
  * Class User.
  * @mixin Authenticatable|Model
  */
 class User extends Model implements Authenticatable
 {
     protected $table = 'user';

     protected $fillable = [
         'name',
         'key',
         'status',
         'password',
     ];

     protected $hidden = [
         'id',
         'password',
     ];

     public function getId()
     {
         return $this->getKey();
     }

     public static function retrieveById($key): ?Authenticatable
     {
         /* @var User $user */
         if (is_int($key)) {
             $user = User::query()->find($key);
         } else {
             $user = User::query()->where('name', $key['name'])->where('password', hash('sha256', $key['password']))->first();
         }
         return $user ?? null;
     }
 }
