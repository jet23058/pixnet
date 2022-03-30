<?php

namespace App\Services\Forwarders;

/**
 * Interface Forwarder
 * @package App\Services\Forwarders
 */
interface Forwarder
{
    /**
     * @param string $area
     * @param float $weight
     * @return int
     */
    public function calculate(string $area, float $weight): int;
}
