<?php

namespace App\Http\Requests\App;

use App\Models\App;
use App\Http\Requests\PaginateRequest as BasePaginateRequest;

class PaginateRequest extends BasePaginateRequest
{
    /** @var string */
    protected $model_classname = App::class;
}
