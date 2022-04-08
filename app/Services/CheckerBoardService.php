<?php

namespace App\Services;

/**
 * Class CheckerBoardService
 * @package App\Services
 */
class CheckerBoardService
{
    /** @var string 未有任何結果 */
    public const NO_RESULT = 'no solution';

    /** @var array */
    private array $map;

    /** @var */
    private int $mapX;

    /** @var */
    private int $mapY;

    /** @var int */
    public int $step = 0;

    /** @var int */
    public int $bestStep = 100;

    /**
     * 取得座標
     * @param array $map
     * @param string $name
     * @return array
     */
    public function getCoordinate(array $map, string $name): array
    {
        $location = [];
        foreach ($map as $index => $item) {
            $column = array_search($name, $item, true);
            if ($column === false) {
                continue;
            }
            $location['row'] = $index;
            $location['column'] = $column;
        }

        return $location;
    }

    /**
     * 老鼠排列組合
     * @param array $targets
     * @param array $result
     * @param int $offset
     */
    public function getPermutations(array $targets, array &$result, int $offset = 0): void
    {
        $count = count($targets);

        if ($count === $offset) {
            $result[] = $targets;
            return;
        }

        for ($index = $offset; $index < $count; ++$index) {
            $temp = $targets[$index];
            $targets[$index] = $targets[$offset];
            $targets[$offset] = $temp;

            $this->getPermutations($targets, $result, $offset + 1);
        }
    }

    /**
     * @param array $permutation
     * @param array $map
     * @param array $cat
     * @return array
     * @throws \Throwable
     */
    public function find(array $permutation, array $map, array $cat): array
    {
        $mouses = [];
        foreach ($permutation as $mouseName) {
            foreach ($map as $index => $item) {
                $column = array_search($mouseName, $item, true);
                if ($column === false) {
                    continue;
                }
                $mouses[$mouseName]['row'] = $index;
                $mouses[$mouseName]['column'] = $column;
            }
        }
        throw_if(empty($mouses), new \Exception('mouse notfound.'));

        $countRow = count($map);
        $countColumn = count($map[0]);
        $totalSize = $countRow * $countColumn;

        $map[$cat['row']][$cat['column']] = 0;
        $this->map = $map;
        $this->mapX = $countRow;
        $this->mapY = $countColumn;

        $row = $cat['row'];
        $column = $cat['column'];

        $result = [];
        foreach ($mouses as $name => $mouse) {
            // set
            $this->bestStep = $totalSize + 1;
            $this->step = 0;

            $bestAnswer = $this->solution($row, $column, $name);

            // set next location
            $row = $mouse['row'];
            $column = $mouse['column'];
            $map[$row][$column] = 0;
            $this->map = $map;

            $result[$name] = $bestAnswer >= $totalSize ? self::NO_RESULT : $bestAnswer;
        }

        return $result;
    }

    /**
     * @param int $row
     * @param int $column
     * @param string $target
     * @return int
     */
    public function solution(int $row, int $column, string $target): int
    {
        if ($row < 0 || $column < 0 || $row >= $this->mapX || $column >= $this->mapY) {
            return 0;
        }

        if ($this->map[$row][$column] === $target) {
            if ($this->step < $this->bestStep) {
                $this->bestStep = $this->step;
            }
            return 0;
        }

        if (
            ($this->map[$row][$column] !== 0 && $this->map[$row][$column] !== $target) ||
            $this->step === $this->bestStep
        ) {
            return 0;
        }

        $this->map[$row][$column] = $this->step;
        $this->step++;
        $this->solution($row + 1, $column, $target);
        $this->solution($row - 1, $column, $target);
        $this->solution($row, $column + 1, $target);
        $this->solution($row, $column - 1, $target);
        $this->map[$row][$column] = 0;
        $this->step--;

        return $this->bestStep - 1;
    }

    /**
     * 組成結果需顯示的樣子
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
