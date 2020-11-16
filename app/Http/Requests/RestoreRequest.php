<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\RestoreRequestInterface;

class RestoreRequest extends Request implements RestoreRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ($this->authorizeResourceDeletedExist() === false) {
            return false;
        }

        return $this->user()->can('restore', $this->resource);
    }
}
