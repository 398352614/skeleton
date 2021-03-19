<?php
/**
 * Created by NLE.TECH INC.
 * User : Crazy_Ning
 * Date : 3/11/2021
 * Time : 3:23 PM
 * Email: nzl9851@88.com
 * Blog : nizer.in
 * FileName: CarAccidentResource.php
 */


namespace App\Http\Resources\Api\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CarAccidentResource
 * @package App\Http\Resources\Api\Admin
 */
class CarAccidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
