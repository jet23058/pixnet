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
            'falcon, taiwan >= 5' => [
                'adapter' => 'Falcon',
                'area' => 'Taiwan',
                'weight' => 80.5,
                'expected' => 2430,
            ],
            'falcon, taiwan < 5' => [
                'adapter' => 'Falcon',
                'area' => 'Taiwan',
                'weight' => 3.3,
                'expected' => 150,
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
    public function test_運費計算成功(string $adapter, string $area, float $weight, int $expected): void
    {
        // arrange

        // action
        $result = app(PixnetController::class)->calculateFreight($adapter, $area, $weight);

        // assert
        $this->assertSame($result, $expected);
    }

    public function test_運費計算失敗(): void
    {
        // arrange
        $adapter = 'Mouse';
        $area = 'Taiwan';
        $weight = 100;

        // assert
        $this->expectException(\Exception::class);

        // action
        app(PixnetController::class)->calculateFreight($adapter, $area, $weight);
    }

    public function getSolutionData(): array
    {
        return [
            'case 1' => [
                'request' => [
                    [0, 0, 0, 0, 0, 1, 0, 0],
                    [0, 'C', 0, 0, 0, 1, 0, 0],
                    [0, 1, 1, 0, 'Z', 0, 0, 0],
                    [0, 1, 1, 1, 0, 0, 0, 0],
                    [0, 'X', 1, 0, 0, 0, 0, 0],
                    [0, 0, 1, 'Y', 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0, 0, 0],
                ],
                'expected' => '3Z3Y4X',
            ],
            'case 2' => [
                'request' => [
                    [0, 0, 0, 0, 'Y', 1, 0, 0],
                    [0, 'C', 0, 1, 1, 1, 0, 0],
                    [0, 1, 1, 1, 'X', 0, 0, 0],
                ],
                'expected' => 'no solution',
            ],
            'case 3' => [
                'request' => [
                    [0, 0, 0, 0, 'Y', 1, 0, 0],
                    [0, 'C', 0, 1, 1, 1, 0, 0],
                ],
                'expected' => '3Y',
            ],
        ];
    }

    /**
     * @dataProvider getSolutionData
     * @param array $request
     * @param string $expected
     * @throws \Exception
     */
    public function test_貓抓老鼠(array $request, string $expected): void
    {
        // arrange

        // action
        $result = app(PixnetController::class)->solution($request);

        // assert
        $this->assertSame($result, $expected);
    }
}
