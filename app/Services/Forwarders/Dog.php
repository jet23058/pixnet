<?php

namespace App\Services\Forwarders;

/**
 * Class Dog
 * @package App\Services\Forwarders
 */
class Dog implements Forwarder
{
    /**
     * @param string $area
     * @param float $weight
     * @return int
     */
    public function calculate(string $area, float $weight): int
    {
        switch ($area) {
            case 'America':
                return 0 + (ceil($weight) * 60);
                break;
            default:
                throw new Exception('area notfound.');
                break;
        }
    }
}
