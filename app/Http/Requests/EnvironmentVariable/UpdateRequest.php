<?php

namespace App\Http\Requests\EnvironmentVariable;

use App\Http\Requests\UpdateRequest as BaseUpdateRequest;
use App\Repositories\AppRepository;
use App\Repositories\EnvironmentVariableRepository;
use Illuminate\Validation\Rule;

class UpdateRequest extends BaseUpdateRequest
{
    /**
     * Create a new request instance.
     *
     * @param \App\Repositories\EnvironmentVariableRepository $repository
     * @param \App\Repositories\AppRepository $apps
     * @return void
     */
    public function __construct(
        EnvironmentVariableRepository $repository,
        private AppRepository $apps,
    ) {
        $this->repository = $repository;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            // Can not change `app_id`
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9-_]+$/i',
                Rule::uniqueCaseInsensitive(
                    $this->repository->getModelTable()
                )->where(
                    fn ($query) => $query->whereNull('deleted_at')
                        // `$this->resource` will be defined from the authorization phase
                        ->where('app_id', $this->resource->app_id)
                        ->where('id', '<>', $this->route('id'))
                ),
            ],
            'value' => [
                'nullable',
                'string',
                'max:65536',
            ],
        ];

        return $this->filterProvidedInputRules($rules);
    }
}
