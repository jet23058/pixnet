<?php

namespace App\Services\Forwarders;

/**
 * Class Falcon
 * @package App\Services\Forwarders
 */
class Falcon
{
    /**
     * 不足的公斤數仍然進位
     * @param string $area
     * @param float $weight
     * @return int
     */
    public function calculate(string $area, float $weight): int
    {
        switch ($area) {
            case 'China':
                return 200 + (ceil($weight) * 20);
                break;
            case 'Taiwan':
                $extra = $weight >= 5 ? ceil($weight - 5) * 30 : 0;
                return 150 + $extra;
                break;
            default:
                throw new Exception('area notfound.');
                break;
        }
    }
}
