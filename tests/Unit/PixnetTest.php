<?php

namespace Tests\Unit;

use App\Http\Controllers\PixnetController;
use Tests\TestCase;

/**
 * Class PixnetTest
 * @package Tests\Unit
 */
class PixnetTest extends TestCase
{
    public function getCalculateFreightSuccessData(): array
    {
        return [
            'dog, america' => [
                'adapter' => 'Dog',
                'area' => 'America',
                'weight' => 80.5,
                'expected' => 4860,
            ],
            'falcon, china' => [
                'adapter' => 'Falcon',
                'area' => 'China',
                'weight' => 80.5,
                'expected' => 1820,
            ],
            'falcon, taiwan' => [
                'adapter' => 'Falcon',
                'area' => 'Taiwan',
                'weight' => 80.5,
                'expected' => 2430,
            ],
            'cat, taiwan' => [
                'adapter' => 'Cat',
                'area' => 'Taiwan',
                'weight' => 80.5,
                'expected' => 370,
            ],
        ];
    }

    /**
     * @dataProvider getCalculateFreightSuccessData
     * @param string $adapter
     * @param string $area
     * @param float $weight
     * @param int $expected
     * @throws \Exception
     */
    public function test_運費計算(string $adapter, string $area, float $weight, int $expected): void
    {
        // arrange

        // action
        $result = app(PixnetController::class)->calculateFreight($adapter, $area, $weight);

        // assert
        $this->assertSame($result, $expected);
    }
}
