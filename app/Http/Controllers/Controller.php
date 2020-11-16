<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contracts\DestroyRequestInterface;
use App\Http\Requests\Contracts\StoreRequestInterface;
use App\Http\Requests\Contracts\UpdateRequestInterface;
use Illuminate\Container\Container;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * The controller-specific bindings.
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * The service implementation.
     *
     * @var \App\Services\ResourceServiceInterface
     */
    protected $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->addBindings();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Http\Requests\Contracts\DestroyRequestInterface $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequestInterface $request, $id)
    {
        // Destroy resource
        $resource = $this->service->destroy($request, $id);

        return redirect($this->getBaseRoute())->with('resource_destroy_success', true)
            ->with('resource_name', $this->service->getResourceName());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Contracts\StoreRequestInterface $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequestInterface $request)
    {
        // Store resource
        $resource = $this->service->store($request);

        return back()->with('resource_store_success', true)
            ->with('resource_name', $this->service->getResourceName());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Contracts\UpdateRequestInterface $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequestInterface $request, $id)
    {
        // Update resource
        $resource = $this->service->update($request, $id);

        return back()->with('resource_update_success', true)
            ->with('resource_name', $this->service->getResourceName());
    }

    /**
     * Return the service name.
     *
     * @return string
     */
    protected function getServiceName(): string
    {
        return $this->service->getName();
    }

    /**
     * Return the base route.
     *
     * @return string
     */
    protected function getBaseRoute(): string
    {
        return Str::slug($this->getServiceName());
    }

    /**
     * Return the base route.
     *
     * @return string
     */
    protected function getViewBaseFolder(): string
    {
        return Str::slug($this->getServiceName());
    }

    /**
     * Add controller specific bindings.
     *
     * @return void
     */
    protected function addBindings(): void
    {
        $app = Container::getInstance();

        foreach ($this->getBindings() as $abstract => $concrete) {
            $app->bind($abstract, $concrete);
        }
    }

    /**
     * Return the controller bindings.
     *
     * @return array
     */
    protected function getBindings(): array
    {
        return $this->bindings;
    }
}
