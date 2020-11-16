<?php

namespace App\Http\Requests\App;

use App\Http\Requests\ShowRequest as BaseShowRequest;
use App\Repositories\AppRepository;

class ShowRequest extends BaseShowRequest
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
