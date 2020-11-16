<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\UpdateRequestInterface;

class UpdateRequest extends Request implements UpdateRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
    public function rules(): array
    {
        return [];
    }

    /**
     * Return rules for which the input has been provided.
     *
     * @param array $rules
     * @return array
     */
    protected function filterProvidedInputRules(array $rules): array
    {
        // Get necessary rules based on input (same keys basically)
        return array_intersect_key($rules, $this->all());
    }
}
