<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PixnetService;
use Exception;

/**
 * Class PixnetController
 * @package App\Http\Controllers\Front
 */
class PixnetController extends Controller
{
    /** @var PixnetService */
    private $service;

    /**
     * PixnetController constructor.
     * @param PixnetService $service
     */
    public function __construct(PixnetService $service)
    {
        $this->service = $service;
    }

    /**
     * Q1
     * @param string $user_id
     * @param string $article_id
     * @throws Exception
     */
    public function getUserArticles(string $user_id, string $article_id)
    {
        if (!$user_id || !$article_id) {
            return null;
        }

        $user = User::getUser($user_id);
        if (empty($user)) {
            throw new Exception('查無此帳號!');
        }

        $blog = $user->blog;
        if (empty($blog)) {
            throw new Exception('帳號尚未有部落格!');
        }

        $article = $blog->getArticle($article_id);
        if (empty($article)) {
            throw new Exception('此帳號無此文章!');
        }

        return $article;
    }

    /**
     * Q2 & Q4 (test)
     * @param string $adapter
     * @param string $area
     * @param float $weight
     * @return int
     * @throws Exception
     */
    public function calculateFreight(string $adapter, string $area, float $weight): int
    {
        $forwarder = $this->service->adapter($adapter);

        return $forwarder->calculate($area, $weight);
    }

    /**
     * Q3
     * @param array $map
     * @return string
     * @throws \Throwable
     */
    public function solution(array $map): string
    {
        $catName = 'C';

        // 取得貓位置
        $cat = [];
        foreach ($map as $index => $item) {
            $column = array_search($catName, $item, true);
            if ($column === false) {
                continue;
            }
            $cat['row'] = $index;
            $cat['column'] = $column;
        }
        throw_if(empty($cat), new Exception('至少一隻貓'));

        $permutations = [];
        $mouseNames = ['X', 'Y', 'Z'];
        $this->getCombinationToString($mouseNames, $permutations);
        $final = [];
        foreach ($permutations as $permutation) {
            $result = $this->mappings($permutation, $map, $cat);
            $key = in_array($this->service::NO_RESULT, $result, true) ? $this->service::NO_RESULT : $this->service->getSolutionFormat($result);
            $final[$key] = $key === $this->service::NO_RESULT ? 'error' : array_sum($result);
        }

        return array_search(min($final), $final, true);
    }

    /**
     * @param array $permutation
     * @param array $map
     * @param array $cat
     * @return array
     * @throws \Throwable
     */
    public function mappings(array $permutation, array $map, array $cat): array
    {
        // 取得老鼠位置
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
        throw_if(empty($mouses), new Exception('至少一隻老鼠'));

        $countRow = count($map);
        $countColumn = count($map[0]);

        $map[$cat['row']][$cat['column']] = 0;
        $this->service->setMap($map);
        $this->service->setMapX($countRow);
        $this->service->setMapY($countColumn);

        $row = $cat['row'];
        $column = $cat['column'];

        $index = 0;
        $result = [];
        foreach ($mouses as $name => $mouse) {
            $this->service->bestStep = $countRow * $countColumn + 1;
            $this->service->step = 0;
            $this->service->bestMap = 0;
            $this->service->solution($row, $column, $name);

            $row = $mouse['row'];
            $column = $mouse['column'];
            $map[$row][$column] = 0;
            $this->service->setMap($map);

            $result[$name] = $this->service->getBestStep() === $countRow * $countColumn ? $this->service::NO_RESULT : $this->service->getBestStep();

            $index++;
        }

        return $result;
    }

    /**
     * @param array $arr
     * @param array $result
     * @param int $offset
     */
    public function getCombinationToString(array &$arr, array &$result, int $offset = 0): void
    {
        $m = count($arr);

        if ($m === $offset) {
            $result[] = $arr;
            return;
        }

        for ($i = $offset; $i < $m; ++$i) {
            $tmp = $arr[$i];
            $arr[$i] = $arr[$offset];
            $arr[$offset] = $tmp;
            $this->getCombinationToString($arr, $result, $offset + 1);
            $tmp = $arr[$i];
            $arr[$i] = $arr[$offset];
            $arr[$offset] = $tmp;
        }
    }
}
