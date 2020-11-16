<?php

namespace App\Http\Requests\App;

use App\Http\Requests\CreateRequest as BaseCreateRequest;
use App\Models\App;

class CreateRequest extends BaseCreateRequest
{
    /** @var string */
    protected $model_classname = App::class;
}
