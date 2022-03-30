<?php

namespace App\Services;

use App\Services\Forwarders\Cat;
use App\Services\Forwarders\Dog;
use App\Services\Forwarders\Falcon;

/**
 * Class PixnetService
 * @package App\Services
 */
class PixnetService
{
    /** @var string  */
    const ADAPTER_DOG = 'Dog';

    /** @var string  */
    const ADAPTER_FALCON = 'Falcon';

    /** @var string  */
    const ADAPTER_CAT = 'Cat';

    /**
     * @param string $adapter
     * @return Cat|Dog|Falcon
     * @throws \Exception
     */
    public function adapter(string $adapter)
    {
        switch ($adapter) {
            case self::ADAPTER_DOG:
                return new Dog();
                break;
            case self::ADAPTER_FALCON:
                return new Falcon();
                break;
            case self::ADAPTER_CAT:
                return new Cat();
                break;
        }
        throw new \Exception('error adapter.');
    }
}
