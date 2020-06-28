<?php

namespace App\Http\Requests;

use App\Helpers\ValidationRuleHelper;
use App\Http\Requests\Contracts\UpdateRequestInterface;

class UpdateRequest extends Request implements UpdateRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->authorizeResourceExist() === false) {
            return false;
        }

        return $this->user()->can('update', $this->resource);
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
            ->getRules(get_class($this->resource));
    }
}
