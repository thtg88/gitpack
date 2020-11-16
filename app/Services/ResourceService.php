<?php

namespace App\Services;

use App\Http\Requests\Contracts\DestroyRequestInterface;
use App\Http\Requests\Contracts\IndexRequestInterface;
use App\Http\Requests\Contracts\RestoreRequestInterface;
use App\Http\Requests\Contracts\SearchRequestInterface;
use App\Http\Requests\Contracts\StoreRequestInterface;
use App\Http\Requests\Contracts\UpdateRequestInterface;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ResourceService implements ResourceServiceInterface
{
    use Concerns\WithPagination;

    /**
     * The repository implementation.
     *
     * @var \App\Repositories\RepositoryInterface
     */
    protected $repository;

    /**
     * Deletes a model instance from a given id.
     *
     * @param \App\Http\Requests\Contracts\DestroyRequestInterface $request
     * @param int $id The id of the model.
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function destroy(DestroyRequestInterface $request, $id): ?Model
    {
        return $this->repository->destroy($id);
    }

    /**
     * Return the service name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->repository->getModelTable();
    }

    /**
     * Return the title-cased resource name.
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return Str::title(
            str_replace(
                '_',
                ' ',
                Str::singular($this->getName())
            )
        );
    }

    /**
     * Return the service name.
     *
     * @return \App\Repositories\Repository
     */
    public function getRepository(): Repository
    {
        return $this->repository;
    }

    /**
     * Return all the model instances.
     *
     * @param \App\Http\Requests\Contracts\IndexRequestInterface $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(IndexRequestInterface $request): Collection
    {
        return $this->repository->all();
    }

    /**
     * Restore a model instance from a given id.
     *
     * @param \App\Http\Requests\Contracts\RestoreRequestInterface $request
     * @param int $id The id of the model
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function restore(RestoreRequestInterface $request, $id): ?Model
    {
        return $this->repository->restore($id);
    }

    /**
     * Return the model instances matching the given search query.
     *
     * @param \App\Http\Requests\Contracts\SearchRequestInterface $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(SearchRequestInterface $request): Collection
    {
        // Get search query
        $query = $request->q;

        return $this->repository->search($query);
    }

    /**
     * Returns a model from a given id.
     *
     * @param int $id The id of the instance.
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function show($id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * Create a new model instance in storage from the given request.
     *
     * @param \App\Http\Requests\Contracts\StoreRequestInterface $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(StoreRequestInterface $request): Model
    {
        // Get request data
        $data = $request->validated();

        return $this->repository->create($data);
    }

    /**
     * Updates a model instance with given request, and id.
     *
     * @param \App\Http\Requests\Contracts\UpdateRequestInterface $request
     * @param int $id The id of the model
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function update(UpdateRequestInterface $request, $id): ?Model
    {
        // Get request data
        $data = $request->validated();

        return $this->repository->update($id, $data);
    }
}
