<?php

namespace App\Http\Controllers;

use App\Http\Requests\App\DestroyRequest;
use App\Http\Requests\App\PaginateRequest;
use App\Http\Requests\App\ShowRequest;
use App\Http\Requests\App\StoreRequest;
use App\Http\Requests\App\UpdateRequest;
use App\Http\Requests\Contracts\DestroyRequestInterface;
use App\Http\Requests\Contracts\PaginateRequestInterface;
use App\Http\Requests\Contracts\ShowRequestInterface;
use App\Http\Requests\Contracts\StoreRequestInterface;
use App\Http\Requests\Contracts\UpdateRequestInterface;
use App\Services\AppService;

class AppController extends Controller
{
    /**
     * The controller-specific bindings.
     *
     * @var string[]|callable[]
     */
    protected $bindings = [
        DestroyRequestInterface::class => DestroyRequest::class,
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
}
