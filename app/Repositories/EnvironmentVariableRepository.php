<?php

namespace App\Repositories;

use App\Models\EnvironmentVariable;

class EnvironmentVariableRepository extends Repository
{
    protected static $model_name = 'id';

    protected static $order_by_columns = [
        'name' => 'asc',
    ];

    protected static $search_columns = [
        'name',
        'value',
    ];

    protected static $filter_columns = [
        'app_id',
    ];

    /**
     * Create a new repository instance.
     *
     * @param \App\Models\EnvironmentVariable $model
     * @return void
     */
    public function __construct(EnvironmentVariable $model)
    {
        $this->model = $model;

        parent::__construct();
    }
}
