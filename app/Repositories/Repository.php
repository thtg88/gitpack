<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Repository implements RepositoryInterface
{
    use Concerns\WithAllModels,
        Concerns\WithCreate,
        Concerns\WithDestroy,
        Concerns\WithFind,
        Concerns\WithGet,
        Concerns\WithPagination,
        Concerns\WithSearch,
        Concerns\WithUpdate;

    /**
     * The repository model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The columns to use for date filtering.
     *
     * @var array
     */
    protected static $date_filter_columns;

    /**
     * The column to treat as the model name.
     *
     * @var string
     * @TODO expand to include multiple column functionality. Perhaps allow array, with separator additional parameter, or closure.
     */
    protected static $model_name;

    /**
     * The columns to which perform the sorting on.
     *
     * @var array
     */
    protected static $order_by_columns;

    /**
     * The columns to which perform the search on.
     *
     * @var array
     */
    protected static $search_columns;

    /**
     * The columns to which perform filtering on.
     *
     * @var array
     */
    protected static $filter_columns;

    /**
     * Whether or not to include trashed resources in the query.
     *
     * @var bool
     */
    protected $with_trashed;

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Get model class name
        $classname = get_class($this->model);

        // Get target table for model
        $target_type = $this->model->getTable();

        // Merge into morph map for accessibility across the App when retrieving
        Relation::morphMap([
            $target_type => $classname,
        ]);

        // Omit trashed model by default
        $this->with_trashed = false;
    }

    /**
     * Return the repository model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Return the repository model table.
     *
     * @return string
     */
    public function getModelTable(): string
    {
        return $this->getModel()->getTable();
    }

    /**
     * Return the columns to filter by the model.
     *
     * @return array
     */
    public function getDefaultFilterColumns(): array
    {
        return static::$filter_columns;
    }

    /**
     * Return the columns to order by the repository model.
     *
     * @return array
     */
    public function getDefaultOrderByColumns(): array
    {
        return static::$order_by_columns;
    }

    /**
     * Return the columns to search by the repository model.
     *
     * @return array
     */
    public function getDefaultSearchColumns(): array
    {
        return static::$search_columns;
    }

    /**
     * Exclude a scope for the model in the current repository
     *
     * @param string $scope_classname
     * @return self
     */
    public function withGlobalScope(string $scope_classname): self
    {
        $this->model = $this->model->addGlobalScope($scope_classname);

        return $this;
    }

    /**
     * Exclude a scope for the model in the current repository
     *
     * @param string $scope_classname
     * @return self
     */
    public function withoutGlobalScope(string $scope_classname): self
    {
        $this->model = $this->model->withoutGlobalScope($scope_classname);

        return $this;
    }

    /**
     * Include trashed models for the current repository.
     *
     * @return self
     */
    public function withTrashed(): self
    {
        $this->with_trashed = true;

        return $this;
    }

    /**
     * Exclude trashed models for the current repository.
     *
     * @return self
     */
    public function withoutTrashed(): self
    {
        $this->with_trashed = false;

        return $this;
    }

    /**
     * Return a query builder that builds the default SQL order by,
     * from a given existing query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model $builder
     * @return \Illuminate\Database\Query\Builder
     */
    protected function withDefaultOrderBy($bulder)
    {
        if (
            ! is_array(static::$order_by_columns) ||
            count(static::$order_by_columns) === 0
        ) {
            return $bulder;
        }

        foreach (static::$order_by_columns as $order_by_column => $direction) {
            $bulder = $bulder->orderBy($order_by_column, $direction);
        }

        return $bulder;
    }

    /**
     * Return a query builder that optionally includes the `withTrashed`
     * in the given builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model $builder
     * @return \Illuminate\Database\Query\Builder
     */
    protected function withOptionalTrashed($builder)
    {
        // Get a list of traits used by the class
        $class_uses = class_uses($this->model);

        // We assume all models use SoftDeletes,
        // as it's defined in our Model class
        // The array check below will return false
        // as class_uses does not check the parent class
        // (where SoftDeletes will be imported)
        if (
            $this->with_trashed === true
            // && in_array('Illuminate\Database\Eloquent\SoftDeletes', $class_uses)
        ) {
            $builder = $builder->withTrashed();
        }

        return $builder;
    }
}
