<?php

namespace App\Http\Controllers;

use App\Http\Requests\App\CreateRequest;
use App\Http\Requests\App\DestroyRequest;
use App\Http\Requests\App\EditRequest;
use App\Http\Requests\App\PaginateRequest;
use App\Http\Requests\App\ShowRequest;
use App\Http\Requests\App\StoreRequest;
use App\Http\Requests\App\UpdateRequest;
use App\Http\Requests\Contracts\CreateRequestInterface;
use App\Http\Requests\Contracts\DestroyRequestInterface;
use App\Http\Requests\Contracts\EditRequestInterface;
use App\Http\Requests\Contracts\PaginateRequestInterface;
use App\Http\Requests\Contracts\ShowRequestInterface;
use App\Http\Requests\Contracts\StoreRequestInterface;
use App\Http\Requests\Contracts\UpdateRequestInterface;
use App\Services\AppService;

class AppController extends CrudController
{
    /**
     * The controller-specific bindings.
     *
     * @var string[]|callable[]
     */
    protected $bindings = [
        CreateRequestInterface::class => CreateRequest::class,
        DestroyRequestInterface::class => DestroyRequest::class,
        EditRequestInterface::class => EditRequest::class,
        PaginateRequestInterface::class => PaginateRequest::class,
        ShowRequestInterface::class => ShowRequest::class,
        StoreRequestInterface::class => StoreRequest::class,
        UpdateRequestInterface::class => UpdateRequest::class,
    ];

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\AppService $service
     * @return void
     */
    public function __construct(AppService $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Http\Requests\Contracts\EditRequestInterface $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRequestInterface $request, $id)
    {
        $resource = $this->service->show($id)->load([
            'environment_variables' => static function ($query) {
                $query->orderBy('name');
            },
        ]);

        return view($this->getViewBaseFolder().'.edit.main')
            ->with('resource', $resource);
    }
}
