<?php

namespace App\Services;

use App\Http\Requests\Api\FetchResourcesRequest;
use App\Models\Reviews;
use App\Services\Resources\EpisodeResource;
use NickBeen\RickAndMortyPhpApi\Episode;
use NickBeen\RickAndMortyPhpApi\Exceptions\NotFoundException;

/**
 * Класс, реализующий логику по работе с эпизодами из API "Rick and Morty"
 *
 * Class EpisodeService
 *
 * @package App\Services
 */
class EpisodeService
{
    /**
     * Возвращает коллекцию ресурсов согласно указанным параметрам FetchResourcesRequest
     *
     * @param  FetchResourcesRequest  $request
     *
     * @return array
     * @throws NotFoundException
     */
    public function getListData(FetchResourcesRequest $request): array
    {
        $episode = new Episode();
        $episodes = $episode->page($request->pageNumber())->get();

        return (new EpisodeResource)->collection($episodes);
    }

    /**
     * Возвращает ресурс по id
     *
     * @param  int  $id
     *
     * @return array
     * @throws NotFoundException
     */
    public function getItemData(int $id): array
    {
        $episodes = new Episode();
        /** @var \NickBeen\RickAndMortyPhpApi\Dto\Episode $episode */
        $episode = $episodes->get($id);

        $reviewsSql = Reviews::query()->where(
            'episode', $id
        );
        $avgRating = $reviewsSql->avg('rating');
        $reviews = $reviewsSql->orderByDesc('rating')->limit(3)->get();

        return (new EpisodeResource)->instance($episode, $reviews, $avgRating);
    }
}
