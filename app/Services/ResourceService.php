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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function destroy(DestroyRequestInterface $request, $id)
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
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(IndexRequestInterface $request): LengthAwarePaginator
    {
        // Get input
        $input = $request->only([
            'page',
            'page_size',
            'recovery',
            'sort_direction',
            'sort_name',
        ]);

        $wheres = [];

        $wheres = array_merge($wheres, $this->getFilterValues($request));

        // Page falls back to 1
        if (! array_key_exists('page', $input) || $input['page'] === null) {
            $input['page'] = 1;
        }

        // Page size fall back to configs
        if (
            ! array_key_exists('page_size', $input) ||
            $input['page_size'] === null
        ) {
            $input['page_size'] = config('app.pagination.page_size');
        }

        $input['q'] = $this->getSearchValue($request);

        if (array_key_exists('recovery', $input) && $input['recovery'] == 1) {
            // Set the repository to also fetch trashed models
            $this->repository = $this->repository->withTrashed();

            $wheres[] = [
                'field' => 'deleted_at',
                'operator' => '<>',
                'value' => null,
            ];
        }

        // Sort name fall back
        if (empty($input['sort_name'])) {
            $input['sort_name'] = null;
        }

        // Sort direction fall back
        if (empty($input['sort_direction'])) {
            $input['sort_direction'] = null;
        }

        // Get paginated resources
        return $this->repository->paginate(
            $input['page_size'],
            $input['page'],
            $input['q'],
            $input['sort_name'],
            $input['sort_direction'],
            $wheres
        );
    }

    /**
     * Restore a model instance from a given id.
     *
     * @param \App\Http\Requests\Contracts\RestoreRequestInterface $request
     * @param int $id The id of the model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function restore(RestoreRequestInterface $request, $id)
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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Create a new model instance in storage from the given request.
     *
     * @param \App\Http\Requests\Contracts\StoreRequestInterface $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(StoreRequestInterface $request)
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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(UpdateRequestInterface $request, $id)
    {
        // Get request data
        $data = $request->validated();

        return $this->repository->update($id, $data);
    }
}
