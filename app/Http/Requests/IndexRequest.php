<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\IndexRequestInterface;

class IndexRequest extends Request implements IndexRequestInterface
{
    /** @var string */
    protected $model_classname;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', $this->model_classname);
    }
}
