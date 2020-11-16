<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\EditRequestInterface;

class EditRequest extends Request implements EditRequestInterface
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
}
