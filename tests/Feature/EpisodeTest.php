<?php

namespace Tests\Feature;

use NickBeen\RickAndMortyPhpApi\Episode;

/**
 * Class EpisodeTest
 *
 * @package Tests\Feature
 * @covers EpisodeTest
 */
class EpisodeTest extends ApiControllerTestCase
{
    public string $baseRouteName = 'api.episodes.';

    /**
     * Получение ресурса
     * Ожидаемый ответ - ресурс
     */
    public function testSuccessfulShow(): void
    {
        $episode = new Episode();

        /** @var \NickBeen\RickAndMortyPhpApi\Dto\Episode $currentEpisode */
        $currentEpisode = $episode->get(1);

        $this->routeName = $this->baseRouteName . 'show';

        $this->callRouteAction([], [$currentEpisode->id])
            ->assertOk()
            ->assertJsonFragment([
                'id' => $currentEpisode->id,
                'attributes' => [
                    'name' => $currentEpisode->name,
                    'air_date' => $currentEpisode->air_date,
                ],
            ])
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes',
                ],
            ]);
    }

    /**
     * Получение списка ресурсов
     * Ожидаемый ответ - список ресурсов
     */
    public function testSuccessfulGetList(): void
    {
        $this->routeName = $this->baseRouteName . 'index';
        $this->callRouteAction()
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    [
                        'type',
                        'id',
                        'attributes',
                    ],
                ],
                'meta',
            ]);
    }

    /**
     * Получение не существующего ресурса
     */
    public function testShowForMissing(): void
    {
        $this->routeName = $this->baseRouteName . 'show';

        $this->callRouteAction([], [0])
            ->assertNotFound()
            ->assertJsonStructure(['errors']);
    }
}