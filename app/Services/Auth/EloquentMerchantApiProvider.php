<?php

namespace App\Services\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class EloquentMerchantApiProvider extends EloquentUserProvider
{
    /**
     * The hasher implementation.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * The Eloquent user model.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new database user provider.
     *
     * @param \Illuminate\Contracts\Hashing\Hasher $hasher
     * @param string $model
     * @return void
     */
    public function __construct(HasherContract $hasher, $model)
    {
        parent::__construct($hasher, $model);
    }


    /**
     * Retrieve a user by their unique identifier.
     * @param mixed $identifier
     * @return UserContract|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function retrieveById($identifier)
    {
        $model = $this->createModel();

        return $this->newModelQuery($model)
            ->where('key', $identifier)
            ->first();
    }


    /**
     * Retrieve a user by the given credentials.
     * @param array $credentials
     * @return UserContract|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        return $this->newModelQuery()->where(['key' => $credentials['key']])->first();
    }


    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $sign = $credentials['sign'];
        $data = $credentials['data'];
        if ($this->hasher->check($user->getAuthPassword(), $sign, $data)) {
            return true;
        }
        return false;
    }
}
