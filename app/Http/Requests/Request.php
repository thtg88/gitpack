<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    /**
     * The request repository implementation.
     *
     * @var \App\Repositories\Repository
     */
    protected $repository;

    /**
     * The model implementation.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $resource;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Return whether the user is the owner of the resource concerning the
     * request or not.
     *
     * @return bool
     */
    protected function authorizeOwner()
    {
        // Get resource
        $this->findResource();

        // Needs to be an existing resource
        if ($this->resource === null) {
            return false;
        }

        // Current user is the owner of the viewing
        return $this->user()->id == $this->resource->user_id;
    }

    /**
     * Determine if the resource from the route's id exists.
     *
     * @return bool
     */
    public function authorizeResourceExist()
    {
        return $this->findResource() !== null;
    }

    /**
     * Determine if the resource (even if deleted) exists.
     *
     * @return bool
     */
    public function authorizeResourceDeletedExist()
    {
        return $this->findResource(true) !== null;
    }

    /**
     * Determine if the resource from the route's id belongs
     * to the user performing the request.
     *
     * @return bool
     */
    public function authorizeResourceExistAndOwner()
    {
        // Find resource
        $this->findResource();

        $user = $this->user();

        return (
            $this->resource !== null &&
            $user !== null &&
            $this->resource->user_id == $user->id
        );
    }

    // protected function formatErrors(Validator $validator)
    // {
    //     return ['errors' => $validator->getMessageBag()->toArray()];
    // }

    /**
     * Find the resource from the route's id.
     * Optionally include a trashed resource in the query.
     *
     * @param bool $with_trashed
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function findResource($with_trashed = false)
    {
        // Get id from route
        $resource_id = (int) $this->route('id');

        if ($with_trashed === true) {
            $this->resource = $this->repository->withTrashed()
                ->find($resource_id);
        } else {
            $this->resource = $this->repository->find($resource_id);
        }

        return $this->resource;
    }
}
