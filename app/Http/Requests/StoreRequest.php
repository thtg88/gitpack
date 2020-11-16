<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\StoreRequestInterface;

class StoreRequest extends Request implements StoreRequestInterface
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
        return $this->user()->can('create', $this->model_classname);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
