<?php

namespace App\Services;

use App\Contracts\Resource;
use App\Http\Requests\Api\FetchResourcesRequest;
use App\Models\Reviews;
use App\Services\Resources\ReviewsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use NickBeen\RickAndMortyPhpApi\Episode;
use NickBeen\RickAndMortyPhpApi\Exceptions\NotFoundException;

/**
 * Класс, реализующий логику по работе с отзывами
 *
 * Class ReviewsService
 *
 * @package App\Services
 */
class ReviewsService
{
    /**
     * Количество элементов на странице по умолчанию
     *
     * @var int
     */
    private int $defaultPageSize = 20;

    /**
     * Возвращает коллекцию ресурсов согласно указанным параметрам FetchResourcesRequest
     *
     * @param  FetchResourcesRequest  $request
     *
     * @return object
     */
    public function getListData(FetchResourcesRequest $request): object
    {
        $query = Reviews::query();

        if ($filterEpisode = Arr::get($request->filter(), 'episode')) {
            $query->where('episode', $filterEpisode);
        }

        foreach ($request->sort() as $sortField => $direction) {
            if (in_array($sortField, $this->sortFields())) {
                $query->orderBy($sortField, $direction);
            }
        }

        $data = $query->paginate(
            $this->defaultPageSize,
            ['*'],
            'page[number]',
            $request->pageNumber()
        )->appends($request->except('page.number'));

        return ReviewsResource::collection($data);
    }

    /**
     * Поля, по которым можно сортировать данные
     *
     * @return array
     */
    public function sortFields(): array
    {
        return [
            'id',
            'episode',
            'rating',
        ];
    }

    /**
     * Возвращает ресурс по id
     *
     * @param  int|string  $id
     *
     * @return ReviewsResource
     */
    public function getItemData(int|string $id): object
    {
        return new ReviewsResource(Reviews::findOrFail($id));
    }

    /**
     * Создание отзыва
     *
     * @param  Request  $request
     *
     * @return Resource
     * @throws NotFoundException
     */
    public function createItem(Request $request): Resource
    {
        $request->validate([
            'episode' => 'required', 'integer',
            'comment' => 'required', 'string', 'max:255',
        ]);
        $idEpisode = $request->get('episode');

        // Запрашиваем информацию по эпизоду, чтобы проверить что такой существует
        $episodes = new Episode();
        /** @var \NickBeen\RickAndMortyPhpApi\Dto\Episode $episode */
        $episodes->get($idEpisode);

        $analysis = new SentimentAnalysis();
        $comment = $request->get('comment');
        $attributes = [
            'comment' => $comment,
            'episode' => $idEpisode,
            'rating' => $analysis->predict($comment),
        ];

        return new ReviewsResource(Reviews::create($attributes));
    }
}
