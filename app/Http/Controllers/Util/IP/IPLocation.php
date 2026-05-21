<?php

namespace App\Http\Controllers\Util\IP;

class IPLocation
{
    public function IpLocation()
    {
        return [
            'ip' => request()->ip() ?? '127.0.0.1'
        ];
    }
}
