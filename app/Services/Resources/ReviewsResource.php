<?php

namespace App\Services\Resources;

use App\Contracts\Resource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Класс, реализующий логику преобразования данных для ответа
 *
 * Class ReviewsResource
 *
 * @package App\Services\Resources
 */
class ReviewsResource extends JsonResource implements Resource
{
    /**
     * @inheritdoc
     */
    public function toArray($request): array
    {
        return [
            'type' => $this->getResourceType(),
            'id' => $this->id,
            'attributes' => [
                'comment' => $this->comment,
                'rating' => $this->rating,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getResourceType(): string
    {
        return 'reviews';
    }
}
