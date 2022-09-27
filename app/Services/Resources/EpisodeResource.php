<?php

namespace App\Services\Resources;

use App\Contracts\Resource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use NickBeen\RickAndMortyPhpApi\Dto\Episode;

/**
 * Класс, реализующий логику преобразования данных для ответа
 *
 * Class EpisodeResource
 *
 * @package App\Services\Resources
 */
class EpisodeResource implements Resource
{
    /**
     * Формирование ответа по коллекции ресурсов
     *
     * @param  array|object  $resource
     *
     * @return array
     */
    public function collection(array|object $resource): array
    {
        $resource = new Collection($resource);

        if (!$resource->isEmpty()) {
            $info = $resource->get('info');
            $items = $resource->get('results');

            $result = array_map(function ($episode) {
                return $this->toArray($episode);
            }, $items);

            return [
                'data' => $result,
                'meta' => $info,
            ];
        }

        return [];
    }

    /**
     * @inheritdoc
     */
    public function toArray(object $episode): array
    {
        return [
            'type' => $this->getResourceType(),
            'id' => $episode->id,
            'attributes' => [
                'name' => $episode->name,
                'air_date' => $episode->air_date,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getResourceType(): string
    {
        return 'episodes';
    }

    /**
     * Формирование ответа для одного ресурса
     *
     * @param  Episode  $resource
     * @param  $reviews
     * @param $avgRating
     *
     * @return array
     */
    public function instance(Episode $resource, $reviews, $avgRating): array
    {
        $data = $this->toArray($resource);
        Arr::set($data, 'attributes.average_rating', $avgRating);

        return [
            'data' => $this->toArray($resource),
            'relationships' => [
                'reviews' => ReviewsResource::collection($reviews),
            ],
        ];
    }
}
