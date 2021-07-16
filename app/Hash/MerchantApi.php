<?php

namespace App\Hash;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Hashing\AbstractHasher;
use Illuminate\Support\Facades\Log;

class MerchantApi extends AbstractHasher implements HasherContract
{
    /**
     * Hash the given value.
     *
     * @param string $value
     * @param array $options
     * @return string
     */
    public function make($value, array $options = array())
    {
        $options = array_filter($options);
        krsort($options);
        $query = $this->dotParams($options, []);
        $str = join('&', $query);
        $sign = strtoupper(md5(urldecode($str . $value)));
        return $sign;
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param string $value
     * @param string $hashedValue
     * @param array $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = array())
    {
        return $this->make($value, $options) === $hashedValue;
    }

    /**
     * Check if the given hash has been hashed using the given options.
     *
     * @param string $hashedValue
     * @param array $options
     * @return bool
     */
    public function needsRehash($hashedValue, array $options = array())
    {
        return false;
    }

    /**
     * 平铺数组
     * @param $params
     * @param $newParams
     * @return mixed
     */
    public function dotParams($params, $newParams)
    {
        foreach ($params as $key => $val) {
            if (is_array($val)) {
                $newParams = $this->dotParams($val, $newParams);
            } else {
                array_push($newParams, $key . '=' . $val);
            }
        }
        return $newParams;
    }
}
