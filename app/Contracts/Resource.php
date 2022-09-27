<?php

namespace App\Contracts;

/**
 * Class Resource
 *
 * @package App\Contracts
 */
interface Resource
{
    /**
     * Тип ресурса
     *
     * @return string
     */
    public function getResourceType(): string;

    /**
     * Преобразовать коллекцию ресурса в массив.
     *
     * @param  object  $episode
     *
     * @return array
     */
    public function toArray(object $episode): array;
}