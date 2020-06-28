<?php

namespace Thtg88\MmCms\Http\Requests\Contracts;

interface ShowRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize();

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules();
}
