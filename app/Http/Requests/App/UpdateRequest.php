<?php

namespace App\Http\Requests\App;

use App\Http\Requests\UpdateRequest as BaseUpdateRequest;
use App\Repositories\AppRepository;
use Illuminate\Validation\Rule;

class UpdateRequest extends BaseUpdateRequest
{
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
        $rules = [
            'aws_client_id' => 'required|string|max:255',
            'aws_client_secret' => 'required|string|max:255',
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9-_]+$/i',
                Rule::uniqueCaseInsensitive(
                    $this->repository->getModelTable()
                )->where(
                    fn ($query) => $query->whereNull('deleted_at')
                        ->where('id', '<>', $this->route('id'))
                ),
            ],
        ];

        return $this->filterProvidedInputRules($rules);
    }
}
