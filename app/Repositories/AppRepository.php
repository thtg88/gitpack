<?php

namespace App\Repositories;

use App\Models\App;

class AppRepository extends Repository
{
    protected static $model_name = 'id';

    protected static $order_by_columns = [
        'id' => 'asc',
    ];

    protected static $search_columns = [
        //
    ];

    protected static $filter_columns = [
        //
    ];

    /**
     * Create a new repository instance.
     *
     * @param \App\Models\App $model
     * @return void
     */
    public function __construct(App $model)
    {
        $this->model = $model;

        parent::__construct();
    }
}
