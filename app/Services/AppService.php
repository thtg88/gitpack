<?php

namespace App\Services;

use App\Http\Requests\Contracts\PaginateRequestInterface;
use App\Http\Requests\Contracts\StoreRequestInterface;
use App\Repositories\AppRepository;
use Illuminate\Database\Eloquent\Model;

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
     * Create a new model instance in storage from the given request.
     *
     * @param \App\Http\Requests\Contracts\StoreRequestInterface $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(StoreRequestInterface $request): Model
    {
        $data = $request->validated();

        $data['user_id'] = $request->user()->id;

        return $this->repository->create($data);
    }
}
