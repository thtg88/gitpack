<?php

namespace App\Http\Requests\App;

use App\Http\Requests\EditRequest as BaseEditRequest;
use App\Repositories\AppRepository;

class EditRequest extends BaseEditRequest
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
