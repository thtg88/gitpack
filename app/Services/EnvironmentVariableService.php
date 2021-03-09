<?php

namespace App\Services;

use App\Repositories\EnvironmentVariableRepository;

class EnvironmentVariableService extends ResourceService
{
    /**
     * Create a new service instance.
     *
     * @param \App\Repositories\EnvironmentVariableRepository $repository
     * @return void
     */
    public function __construct(EnvironmentVariableRepository $repository)
    {
        $this->repository = $repository;
    }
}
