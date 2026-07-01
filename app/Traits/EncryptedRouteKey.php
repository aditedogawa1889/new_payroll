<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait EncryptedRouteKey
{
    /**
     * Initialize the trait to append route_key to JSON representation.
     */
    public function initializeEncryptedRouteKey()
    {
        $this->appends[] = 'route_key';
    }

    /**
     * Accessor for route_key.
     *
     * @return string
     */
    public function getRouteKeyAttribute()
    {
        return $this->getRouteKey();
    }

    /**
     * Get the value of the model's route key.
     *
     * @return string
     */
    public function getRouteKey()
    {
        return Crypt::encryptString($this->getKey());
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $decrypted = Crypt::decryptString($value);
            return $this->where($this->getKeyName(), $decrypted)->firstOrFail();
        } catch (\Exception $e) {
            abort(404);
        }
    }
}
