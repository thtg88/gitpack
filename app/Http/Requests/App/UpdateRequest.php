<?php

namespace App\Http\Requests\App;

use App\Http\Requests\UpdateRequest as BaseUpdateRequest;
use App\Repositories\AppRepository;

class UpdateRequest extends BaseUpdateRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $all_rules = [
            //
        ];

        return $this->filterProvidedInputRules($rules);
    }
}
