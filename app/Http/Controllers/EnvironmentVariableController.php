<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contracts\DestroyRequestInterface;
use App\Http\Requests\Contracts\StoreRequestInterface;
use App\Http\Requests\Contracts\UpdateRequestInterface;
use App\Http\Requests\EnvironmentVariable\DestroyRequest;
use App\Http\Requests\EnvironmentVariable\StoreRequest;
use App\Http\Requests\EnvironmentVariable\UpdateRequest;
use App\Services\EnvironmentVariableService;

class EnvironmentVariableController extends CrudController
{
    /**
     * The controller-specific bindings.
     *
     * @var string[]|callable[]
     */
    protected $bindings = [
        DestroyRequestInterface::class => DestroyRequest::class,
        StoreRequestInterface::class => StoreRequest::class,
        UpdateRequestInterface::class => UpdateRequest::class,
    ];

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\EnvironmentVariableService $service
     * @return void
     */
    public function __construct(EnvironmentVariableService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\Contracts\DestroyRequestInterface $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(DestroyRequestInterface $request, $id)
    {
        // Destroy resource
        $resource = $this->service->destroy($request, $id);

        return redirect()->route('apps.edit', $resource->app_id)
            ->with('resource_destroy_success', true)
            ->with('resource_name', $this->service->getResourceName());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Contracts\StoreRequestInterface $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreRequestInterface $request)
    {
        // Store resource
        $resource = $this->service->store($request);

        return redirect()->route('apps.edit', $resource->app_id)
            ->with('resource_store_success', true)
            ->with('resource_name', $this->service->getResourceName());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Contracts\UpdateRequestInterface $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequestInterface $request, $id)
    {
        // Update resource
        $resource = $this->service->update($request, $id);

        return redirect()->route('apps.edit', $resource->app_id)
            ->with('resource_update_success', true)
            ->with('resource_name', $this->service->getResourceName());
    }
}
