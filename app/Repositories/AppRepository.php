<?php

namespace App\Repositories;

use App\Models\App;

class AppRepository extends Repository
{
    protected static $model_name = 'name';

    protected static $order_by_columns = [
        'name' => 'asc',
    ];

    protected static $search_columns = [
        'name',
    ];

    protected static $filter_columns = [
        'user_id',
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
