<?php

namespace App\Http\Controllers;

use Illuminate\Container\Container;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
     * The csv header row callback implementation.
     *
     * @var \Closure
     */
    protected $csv_header_row_callback;

    /**
     * The csv row callback implementation.
     *
     * @var \Closure
     */
    protected $csv_row_callback;

    /**
     * The service implementation.
     *
     * @var \App\Http\Requests\Contracts\ResourceServiceInterface
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
     * Download a listing of all the resources in CSV format.
     *
     * @param \App\Http\Requests\DownloadCsvRequest $request
     * @return \Illuminate\Http\Response
     */
    public function downloadCsv(DownloadCsvRequest $request)
    {
        // Create download csv helper
        $helper = new DownloadCsvHelper(
            $this->service,
            $this->csv_header_row_callback,
            $this->csv_row_callback
        );

        // Get callback
        $callback = $helper->getCallback($request);

        // Create file name from table name, and timestamp
        $filename = $this->service->getName().'-'.now()->format('Y-m-d\TH_i_s').'.csv';

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$filename,
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        // return $callback();

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Return the service name.
     *
     * @return string
     */
    protected function getServiceName()
    {
        return $this->service->getName();
    }

    /**
     * Return the base route.
     *
     * @return string
     */
    protected function getBaseRoute()
    {
        return Str::slug($this->getServiceName());
    }

    /**
     * Return the base route.
     *
     * @return string
     */
    protected function getViewBaseFolder()
    {
        return Str::slug($this->getServiceName());
    }

    /**
     * Add controller specific bindings.
     *
     * @return void
     */
    protected function addBindings()
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
    protected function getBindings()
    {
        return $this->bindings;
    }
}
