<?php

namespace App\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Http\Requests\Contracts\StoreRequestInterface;

class StoreRequest extends Request implements StoreRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', $this->model_classname);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Container::getInstance()
            ->make(ValidationRuleHelper::class)
            ->getRules($this->model_classname);
    }
}
