<?php

namespace App\Http\Requests\App;

use App\Http\Requests\DestroyRequest as BaseDestroyRequest;
use App\Repositories\AppRepository;

class DestroyRequest extends BaseDestroyRequest
{
    /**
     * Create a new request instance.
     *
     * @param \App\Repositories\AppRepository $repository
     * @return void
     */
    public function __construct(AppRepository $repository)
    {
        $this->repository = $repository;
    }
}
