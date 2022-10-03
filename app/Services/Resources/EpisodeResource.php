<?php

namespace App\Services\Resources;

use App\Contracts\Resource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 * Класс, реализующий логику преобразования данных для ответа
 *
 * Class EpisodeResource
 *
 * @package App\Services\Resources
 */
class EpisodeResource extends JsonResource implements Resource
{
    /**
     * @var mixed
     */
    private mixed $reviews = null;
    /**
     * @var mixed
     */
    private mixed $avgRating = null;

    /**
     * @inheritdoc
     */
    public function toArray($request): array
    {
        $data = [
            'type' => $this->getResourceType(),
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'air_date' => $this->air_date,
            ],
        ];

        if ($this->avgRating) {
            Arr::set($data, 'attributes.average_rating', $this->avgRating);
        }
        if ($this->reviews) {
            Arr::set(
                $data, 'relationships.reviews',
                ReviewsResource::collection($this->reviews)
            );
        }

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function getResourceType(): string
    {
        return 'episodes';
    }

    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    public function setAvgRating($avgRating)
    {
        $this->avgRating = $avgRating;
    }
}
