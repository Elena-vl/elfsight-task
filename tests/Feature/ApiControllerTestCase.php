<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Routing\Route;
use Illuminate\Testing\TestResponse;

/**
 * Класс, содержащий общую логику тестов
 *
 * Class ReviewFactory
 *
 * @package Tests\Feature
 */
abstract class ApiControllerTestCase extends TestCase
{
    /**
     * @var string
     */
    public string $baseRouteName;

    /**
     * Название роута для которого пишутся тесты
     *
     * @var string
     */
    public string $routeName;

    /**
     * Получение объекта текущего роута. Если роут не найдет, то тест будет помечен как fail
     *
     * @return Route
     */
    private function getRouteByName(): Route
    {
        $routes = \Illuminate\Support\Facades\Route::getRoutes();

        /** @var Route $route */
        $route = $routes->getByName($this->routeName);

        if (!$route) {
            $this->fail("Route with name [{$this->routeName}] not found!");
        }

        return $route;
    }

    /**
     * Выполнение запроса
     *
     * @param  array  $data  Request body
     * @param  array  $parameters  Route parameters
     * @param  array  $headers  Request headers
     *
     * @return TestResponse
     */
    protected function callRouteAction(
        array $data = [],
        array $parameters = [],
        array $headers = []
    ): TestResponse {
        $route = $this->getRouteByName();
        $method = $route->methods()[0];
        $url = route($this->routeName, $parameters);

        return $this->json($method, $url, $data, $headers);
    }
}
