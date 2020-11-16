<?php

namespace App\Services;

use App\Http\Requests\Contracts\DestroyRequestInterface;
use App\Http\Requests\Contracts\IndexRequestInterface;
use App\Http\Requests\Contracts\PaginateRequestInterface;
use App\Http\Requests\Contracts\RestoreRequestInterface;
use App\Http\Requests\Contracts\SearchRequestInterface;
use App\Http\Requests\Contracts\StoreRequestInterface;
use App\Http\Requests\Contracts\UpdateRequestInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface ResourceServiceInterface
{
    /**
     * Deletes a model instance from a given id.
     *
     * @param \App\Http\Requests\Contracts\DestroyRequestInterface $request
     * @param int $id The id of the model.
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function destroy(DestroyRequestInterface $request, $id): ?Model;

    /**
     * Return the service name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Return all the model instances.
     *
     * @param \App\Http\Requests\Contracts\IndexRequestInterface $request
     * @return \Illuminate\Support\Collection
     */
    public function index(IndexRequestInterface $request);

    /**
     * Restore a model instance from a given id.
     *
     * @param \App\Http\Requests\Contracts\RestoreRequestInterface $request
     * @param int $id The id of the model
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function restore(RestoreRequestInterface $request, $id): ?Model;

    /**
     * Return the model instances matching the given search query.
     *
     * @param \App\Http\Requests\Contracts\SearchRequestInterface $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(SearchRequestInterface $request): Collection;

    /**
     * Returns a model from a given id.
     *
     * @param int $id The id of the instance.
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function show($id): ?Model;

    /**
     * Create a new model instance in storage from the given data array.
     *
     * @param \App\Http\Requests\Contracts\StoreRequestInterface $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(StoreRequestInterface $request): Model;

    /**
     * Updates a model instance with given data, from a given id.
     *
     * @param \App\Http\Requests\Contracts\UpdateRequestInterface $request
     * @param int $id The id of the model
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function update(UpdateRequestInterface $request, $id): ?Model;
}
