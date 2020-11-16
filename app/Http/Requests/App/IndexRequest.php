<?php

namespace App\Http\Requests\App;

use App\Http\Requests\IndexRequest as BaseIndexRequest;
use App\Repositories\AppRepository;

class IndexRequest extends BaseIndexRequest
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
