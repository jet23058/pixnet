<?php

namespace App\Http\Controllers;

use App\Enums\CheckBoardRoles;
use App\Models\User;
use App\Services\CheckerBoardService;
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

    /** @var CheckerBoardService */
    private CheckerBoardService $checkerBoardService;

    /**
     * PixnetController constructor.
     * @param PixnetService $service
     */
    public function __construct(PixnetService $service, CheckerBoardService $checkerBoardService)
    {
        $this->service = $service;

        $this->checkerBoardService = $checkerBoardService;
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
        $catCoordinate = $this->checkerBoardService->getCoordinate($map, CheckBoardRoles::CAT_NAME);

        if (empty($catCoordinate)) {
            throw new \Exception('cat notfound.');
        }

        $permutations = [];
        $this->checkerBoardService->getPermutations(CheckBoardRoles::MOUSE_NAMES, $permutations);

        $final = [];
        foreach ($permutations as $permutation) {
            $result = $this->checkerBoardService->find($permutation, $map, $catCoordinate);

            if (in_array($this->checkerBoardService::NO_RESULT, $result, true)) {
                $final[$this->checkerBoardService::NO_RESULT] = $this->checkerBoardService::NO_RESULT;
            } else {
                $key = $this->checkerBoardService->getSolutionFormat($result);
                $final[$key] = array_sum($result);
            }
        }

        return array_search(min($final), $final, true);
    }
}
