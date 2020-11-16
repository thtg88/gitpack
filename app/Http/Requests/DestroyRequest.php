<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\DestroyRequestInterface;

class DestroyRequest extends Request implements DestroyRequestInterface
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

        return $this->user()->can('delete', $this->resource);
    }
}
