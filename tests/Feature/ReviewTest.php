<?php

namespace Tests\Feature;

use App\Models\Reviews;

/**
 * Class ReviewTest
 *
 * @package Tests\Feature
 * @covers ReviewTest
 */
class ReviewTest extends ApiControllerTestCase
{
    public string $baseRouteName = 'api.reviews.';

    /**
     * Проверка успешного создания.
     * Ожидаемый ответ - ресурс
     */
    public function testSuccessfulCreate(): void
    {
        $this->routeName = $this->baseRouteName . 'store';

        $data = [
            'episode' => $this->faker->numberBetween(1, 10),
            'comment' => $this->faker->text(50),
        ];

        $this
            ->callRouteAction($data)
            ->assertJsonFragment([
                'comment' => $data['comment'],
            ])
            ->assertCreated()
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes',
                ],
            ]);

        $this->assertDatabaseHas(
            $this->getTableName(), [
                'episode' => $data['episode'],
                'comment' => $data['comment'],
            ]
        );
    }

    private function getTableName(): string
    {
        return $this->getTable(Reviews::class);
    }

    /**
     * Получение ресурса
     * Ожидаемый ответ - ресурс
     */
    public function testSuccessfulShow(): void
    {
        $this->routeName = $this->baseRouteName . 'show';
        Reviews::factory()->count(3)->create();
        $review = Reviews::first();

        $this->callRouteAction([], [$review->id])
            ->assertOk()
            ->assertJsonFragment([
                'id' => $review->id,
                'rating' => $review->rating,
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
     * Этот тест гарантирует, что все обязательные поля для процесса создания
     * заполнены соответствующим образом.
     * Ожидаемый ответ - ошибка
     */
    public function testRequiredFieldsForCreate(): void
    {
        $this->routeName = $this->baseRouteName . 'store';

        $this->callRouteAction([
            'episode' => $this->faker->numberBetween(1, 10),
        ])
            ->assertUnprocessable();
    }

    /**
     * Проверка ошибки создания - не существующий эпизод.
     * Ожидаемый ответ - ошибка
     */
    public function testNotSuccessfulCreate(): void
    {
        $this->routeName = $this->baseRouteName . 'store';

        $this->callRouteAction([
            'episode' => 0,
        ])
            ->assertUnprocessable();
    }

    /**
     * Получение списка ресурсов
     * Ожидаемый ответ - список ресурсов
     */
    public function testSuccessfulGetList(): void
    {
        Reviews::factory()->count(3)->create();
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