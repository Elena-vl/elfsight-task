<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\FetchResourcesRequest;
use App\Services\EpisodeService;
use NickBeen\RickAndMortyPhpApi\Exceptions\NotFoundException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер, отвечающий за работу с эпизодами из API "Rick and Morty"
 *
 * Class EpisodeController
 *
 * @package App\Http\Controllers
 */
class EpisodeController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  FetchResourcesRequest  $request
     *
     * @return object
     */
    public function index(FetchResourcesRequest $request): object
    {
        try {
            return $this->getApiService()->getListData($request);
        } catch (\Exception $e) {
            return $this->renderApiExceptionError(
                $e, Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @return EpisodeService
     */
    protected function getApiService(): EpisodeService
    {
        return new EpisodeService();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return object
     */
    public function show(int $id): object
    {
        try {
            return $this->getApiService()->getItemData($id);
        } catch (NotFoundException $e) {
            return $this->renderApiExceptionError(
                $e, Response::HTTP_NOT_FOUND
            );
        } catch (\Exception $e) {
            return $this->renderApiExceptionError(
                $e, Response::HTTP_BAD_REQUEST
            );
        }
    }
}
