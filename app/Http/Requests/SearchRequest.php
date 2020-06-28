<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\SearchRequestInterface;

class SearchRequest extends Request implements SearchRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('search', $this->model_classname);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'q' => 'required|string|min:2|max:255',
        ];
    }
}
