<?php

namespace App\Services;

use App\Services\Forwarders\Forwarder;

/**
 * Class PixnetService
 * @package App\Services
 */
class PixnetService
{
    /** @var string 未有任何結果 */
    public const NO_RESULT = 'no solution';

    private $map;
    private $mapX;
    private $mapY;

    public $step = 0;
    public $bestStep = 100;
    public $bestMap = 0;

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

    public function setMap(array $value): void
    {
        $this->map = $value;
    }

    public function setMapX(int $value): void
    {
        $this->mapX = $value;
    }

    public function setMapY(int $value): void
    {
        $this->mapY = $value;
    }

    public function solution(int $x, int $y, string $target): void
    {
        if ($x < 0 || $y < 0 || $x >= $this->mapX || $y >= $this->mapY) {
            return;
        }
        if ($this->map[$x][$y] === $target) {
            if ($this->step < $this->bestStep) {
                $this->bestStep = $this->step;
                $this->bestMap = $this->map;
            }
            return;
        }
        if (($this->map[$x][$y] !== 0 && $this->map[$x][$y] !== $target) || $this->step === $this->bestStep) {
            return;
        }
        $this->map[$x][$y] = $this->step;
        $this->step++;
        $this->solution($x + 1, $y, $target);
        $this->solution($x - 1, $y, $target);
        $this->solution($x, $y + 1, $target);
        $this->solution($x, $y - 1, $target);
        $this->map[$x][$y] = 0;
        $this->step--;
    }

    public function getBestStep(): int
    {
        return $this->bestStep - 1;
    }

    /**
     * @param array $result
     * @return string
     */
    public function getSolutionFormat(array $result): string
    {
        $response = [];
        foreach ($result as $key => $item) {
            $response[] = $item . $key;
        }

        return implode('', $response);
    }
}
