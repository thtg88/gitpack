<?php

namespace App\Http\Requests\App;

use App\Http\Requests\StoreRequest as BaseStoreRequest;
use App\Models\App;
use App\Repositories\AppRepository;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseStoreRequest
{
    /** @var string */
    protected $model_classname = App::class;

    /**
     * Create a new request instance.
     *
     * @param \App\Repositories\AppRepository $repository
     * @return void
     */
    public function __construct(AppRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9-_]+$/i',
                Rule::uniqueCaseInsensitive(
                    $this->repository->getModelTable()
                )->where(fn ($query) => $query->whereNull('deleted_at')),
            ],
        ];
    }
}
