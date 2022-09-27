<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 *
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param  \Exception  $e
     * @param  string  $status
     *
     * @return JsonResponse
     */
    protected function renderApiExceptionError(
        \Exception $e,
        string $status
    ): JsonResponse
    {
        $data = [
            'errors' => [
                'title' => $e->getMessage(),
                'status' => $status,
            ],
        ];

        return response()->json($data)->setStatusCode($status);
    }

    /**
     * @param  array|object  $data
     * @param  int  $code
     *
     * @return JsonResponse
     */
    protected function renderApiResponse(array|object $data, int $code = 200): JsonResponse
    {
        return response()->json($data)->setStatusCode($code);
    }
}
