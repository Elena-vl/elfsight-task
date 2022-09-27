<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest as FormRequestParent;

/**
 * Class FetchResourcesRequest
 *
 * @package App\Http\Requests\Api
 */
class FetchResourcesRequest extends FormRequestParent
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'page' => [
                'nullable', 'array',
            ],
            'page.number' => [
                'nullable', 'numeric',
            ],
            'sort' => [
                'nullable', 'string',
            ],
            'filter' => [
                'nullable', 'array',
            ],
        ];
    }

    public function pageNumber(): int
    {
        return (int) ($this->get('page', 1)['number'] ?? 1);
    }

    public function filter(?string $name = null)
    {
        if (!$name) {
            return $this->get('filter', []);
        }

        return $this->get('filter', [])[$name] ?? null;
    }

    public function sort(): array
    {
        $sort = $this->get('sort');

        if (!$sort) {
            return [];
        }

        $sort = explode(',', $sort);

        $sortArray = [];

        foreach ($sort as $value) {
            $sortArray[preg_replace('/^-/', '', $value)] = (str_starts_with(
                $value,
                '-'
            )) ? 'DESC' : 'ASC';
        }

        return $sortArray;
    }
}
