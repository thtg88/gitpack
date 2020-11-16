<?php

namespace App\Http\Requests;

use App\Http\Requests\Contracts\ShowRequestInterface;

class ShowRequest extends Request implements ShowRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (
            $this->get('recovery') == 1 &&
            $this->authorizeResourceDeletedExist() === true
        ) {
            return $this->user()->can('view', $this->resource);
        }

        if ($this->authorizeResourceExist() === false) {
            return false;
        }

        return $this->user()->can('view', $this->resource);
    }
}
