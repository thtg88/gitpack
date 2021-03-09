<?php

namespace App\Http\Requests\EnvironmentVariable;

use App\Http\Requests\StoreRequest as BaseStoreRequest;
use App\Models\EnvironmentVariable;
use App\Repositories\AppRepository;
use App\Repositories\EnvironmentVariableRepository;
use Illuminate\Validation\Rule;

class StoreRequest extends BaseStoreRequest
{
    /** @var string */
    protected $model_classname = EnvironmentVariable::class;

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
            'app_id' => [
                'required',
                'integer',
                Rule::existsWithoutSoftDeleted(
                    $this->apps->getModelTable(),
                    'id'
                )->where(
                    fn ($query) => $query->where('user_id', $this->user()->id)
                ),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9-_]+$/i',
            ],
            'value' => [
                'nullable',
                'string',
                'max:65536',
            ],
        ];

        $app_id = $this->get('app_id');
        if (filter_var($app_id, FILTER_VALIDATE_INT)) {
            $rules['name'][] = Rule::uniqueCaseInsensitive(
                $this->repository->getModelTable()
            )->where(
                fn ($query) => $query->whereNull('deleted_at')
                    ->where('app_id', $app_id)
            );
        }

        return $rules;
    }
}
