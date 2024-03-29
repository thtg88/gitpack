<?php

namespace App\Services;

use App\Http\Requests\Contracts\DestroyRequestInterface;
use App\Http\Requests\Contracts\PaginateRequestInterface;
use App\Http\Requests\Contracts\StoreRequestInterface;
use App\Http\Requests\Contracts\UpdateRequestInterface;
use App\Jobs\GitRemoteRepository\InitJob;
use App\Jobs\GitRemoteRepository\RemoveJob;
use App\Jobs\GitRemoteRepository\RenameJob;
use App\Repositories\AppRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AppService extends ResourceService
{
    /**
     * Create a new service instance.
     *
     * @param \App\Repositories\AppRepository $repository
     * @return void
     */
    public function __construct(AppRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Return the default filter values.
     *
     * @param \App\Http\Requests\Contracts\PaginateRequestInterface $request
     * @return array
     */
    public function getDefaultFilterValues(PaginateRequestInterface $request): array
    {
        return [[
            'name' => 'user_id',
            'operator' => '=',
            'value' => $request->user()->id,
        ]];
    }

    /**
     * Deletes a model instance from a given id.
     *
     * @param \App\Http\Requests\Contracts\DestroyRequestInterface $request
     * @param int $id The id of the model.
     * @return null|\Illuminate\Database\Eloquent\Model
     */
    public function destroy(DestroyRequestInterface $request, $id): ?Model
    {
        $resource = $this->repository->destroy($id);

        if ($resource !== null) {
            dispatch(new RemoveJob($resource));
        }

        return $resource;
    }

    /**
     * Create a new model instance in storage from the given request.
     *
     * @param \App\Http\Requests\Contracts\StoreRequestInterface $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(StoreRequestInterface $request): Model
    {
        $data = $request->validated();

        $data['user_id'] = $request->user()->id;
        $data['uuid'] = Str::uuid();

        $resource = $this->repository->create($data);

        dispatch(new InitJob($resource));

        return $resource;
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
        $old_name = $this->show($id)->name;
        $data = $request->validated();

        $resource = $this->repository->update($id, $data);

        // If name has changed, rename remote repo
        if ($old_name !== $resource->name) {
            dispatch(new RenameJob(
                $resource,
                $old_name,
                $resource->name
            ));
        }

        return $resource;
    }
}
