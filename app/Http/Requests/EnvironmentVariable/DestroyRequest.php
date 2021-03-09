<?php

namespace App\Http\Requests\EnvironmentVariable;

use App\Http\Requests\DestroyRequest as BaseDestroyRequest;
use App\Repositories\EnvironmentVariableRepository;

class DestroyRequest extends BaseDestroyRequest
{
    /**
     * Create a new request instance.
     *
     * @param \App\Repositories\EnvironmentVariableRepository $repository
     * @return void
     */
    public function __construct(EnvironmentVariableRepository $repository)
    {
        $this->repository = $repository;
    }
}
