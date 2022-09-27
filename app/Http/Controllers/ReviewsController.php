<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\FetchResourcesRequest;
use App\Services\ReviewsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use NickBeen\RickAndMortyPhpApi\Exceptions\NotFoundException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер, отвечающий за работу с отзывами на эпизоды "Rick and Morty"
 *
 * Class ReviewsController
 *
 * @package App\Http\Controllers
 */
class ReviewsController extends Controller
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
     * @return ReviewsService
     */
    protected function getApiService(): ReviewsService
    {
        return new ReviewsService();
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
        } catch (ModelNotFoundException $e) {
            return $this->renderApiExceptionError($e, Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->renderApiExceptionError(
                $e, Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     *
     * @return object
     */
    public function store(Request $request): object
    {
        try {
            return $this->getApiService()->createItem($request);
        } catch (NotFoundException | ValidationException $e) {
            return $this->renderApiExceptionError(
                $e, Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (\Exception $e) {
            return $this->renderApiExceptionError(
                $e, Response::HTTP_BAD_REQUEST
            );
        }
    }
}
