<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\CreateRequestInterface;

class CreateRequest extends Request implements CreateRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', $this->model_classname);
    }
}
