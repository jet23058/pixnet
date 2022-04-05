<?php

namespace App\Services;

use App\Services\Forwarders\Forwarder;

/**
 * Class PixnetService
 * @package App\Services
 */
class PixnetService
{
    /**
     * @param string $adapter
     * @return Forwarder
     * @throws \Exception
     */
    public function adapter(string $adapter)
    {
        if (!class_exists("App\Services\Forwarders\\{$adapter}")) {
            throw new \Exception('error adapter.');
        }
        return app("App\Services\Forwarders\\{$adapter}");
    }
}
