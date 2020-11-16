<?php

namespace App\Services;

use App\Repositories\AppRepository;

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
}
