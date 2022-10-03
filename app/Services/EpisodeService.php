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
     * @return object
     * @throws NotFoundException
     */
    public function getListData(FetchResourcesRequest $request): object
    {
        $episode = new Episode();
        $episodes = $episode->page($request->pageNumber())->get();

        $resource = EpisodeResource::collection(collect($episodes->results));
        $resource->additional([
            'meta' => $episodes->info,
        ]);

        return $resource;
    }

    /**
     * Возвращает ресурс по id
     *
     * @param  int  $id
     *
     * @return object
     * @throws NotFoundException
     */
    public function getItemData(int $id): object
    {
        $episodes = new Episode();
        /** @var \NickBeen\RickAndMortyPhpApi\Dto\Episode $episode */
        $episode = $episodes->get($id);

        $reviewsSql = Reviews::query()->where(
            'episode', $id
        );
        $avgRating = $reviewsSql->avg('rating');
        $reviews = $reviewsSql->orderByDesc('rating')->limit(3)->get();

        $resource = new EpisodeResource($episode);
        $resource->setAvgRating($avgRating);
        $resource->setReviews($reviews);

        return $resource;
    }
}
