<?php

namespace App\Services\Forwarders;

/**
 * Class Cat
 * @package App\Services\Forwarders
 */
class Cat
{
    /**
     * @param string $area
     * @param float $weight
     * @return int
     */
    public function calculate(string $area, float $weight): int
    {
        switch ($area) {
            case 'Taiwan':
                return 100 + ceil($weight / 3) * 10;
                break;
            default:
                throw new Exception('area notfound.');
                break;
        }
    }
}
