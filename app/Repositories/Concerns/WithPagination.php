<?php

namespace App\Repositories\Concerns;

use Illuminate\Pagination\LengthAwarePaginator;

trait WithPagination
{
    /**
     * Return the paginated model instances.
     *
     * @param int $page_size The number of model instances to return per page
     * @param int $page The page number
     * @param string $q The optional search query
     * @param string $sort_column The optional sort column
     * @param string $sort_direction The optional sort direction
     * @param array $wheres Additional where clauses
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(
        $page_size = 10,
        $page = null,
        $q = null,
        $sort_column = null,
        $sort_direction = null,
        array $wheres = []
    ): LengthAwarePaginator {
        // Assume page_size as numeric and > 0
        if (empty($page_size) || ! is_numeric($page_size) || $page_size < 1) {
            return new LengthAwarePaginator(
                [],
                0,
                config('app.pagination.page_size')
            );
        }

        // Assume page as numeric and > 0
        if (! empty($page) && (! is_numeric($page) || $page < 1)) {
            return new LengthAwarePaginator(
                [],
                0,
                config('app.pagination.page_size')
            );
        }

        $page_size = floor($page_size);
        $page = floor($page);

        $result = $this->model;

        $result = $this->withOptionalTrashed($result);

        // Add additional where clauses
        if (! empty($wheres)) {
            foreach ($wheres as $key => $where) {
                $result = $this->getWhereFilterQueryBuilder($result, $where);
            }
        }

        // Search clause
        if (! empty($q)) {
            $result = $this->searchQuery($result, $q);
        }

        // Order by clause
        $order_by_set = false;
        if (! empty($sort_column) && ! empty($sort_direction)) {
            if (in_array($sort_direction, ['asc', 'desc'])) {
                if (is_string($sort_column)) {
                    // If sort name and direction included and valid
                    // Order by that
                    $order_by_set = true;
                    $result = $result->orderBy($sort_column, $sort_direction);
                }
            }
        }
        // If no valid order by clause set
        // Order by default
        if ($order_by_set === false) {
            $result = $this->withDefaultOrderBy($result);
        }

        return $result->paginate(
            $page_size,
            config('app.pagination.columns'),
            config('app.pagination.page_name'),
            $page
        );
    }

    /**
     * Return a query builder with the attached filter.
     * If not supported the given query builder will be returned.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model $builder
     * @param array $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getWhereFilterQueryBuilder($builder, array $filter)
    {
        // If I'm not filtering by deleted_at
        // and the column is not in the filter columns repository array
        if (
            (
                ! is_array(static::$filter_columns) ||
                ! in_array($filter['name'], static::$filter_columns)
            )
            && $filter['name'] !== 'deleted_at'
        ) {
            return $builder;
        }

        if (isset($filter['relationship-field'])) {
            $builder = $builder->with($filter['name']);

            if (isset($filter['operator']) && $filter['operator'] === 'in') {
                return $builder->whereHas(
                    $filter['name'],
                    static function ($query) use ($filter) {
                        $query->whereIn(
                            $filter['relationship-field'],
                            $filter['value']
                        );
                    }
                );
            }

            if ($filter['value'] === null) {
                if (
                    ! isset($filter['operator']) ||
                    empty($filter['operator']) ||
                    $filter['operator'] === '='
                ) {
                    return $builder->whereHas(
                        $filter['name'],
                        static function ($query) use ($filter) {
                            $query->whereNull($filter['relationship-field']);
                        }
                    );
                }

                if (
                    isset($filter['operator']) &&
                    (
                        $filter['operator'] === '<>' ||
                        $filter['operator'] === '!='
                    )
                ) {
                    return $builder->whereHas(
                        $filter['name'],
                        static function ($query) use ($filter) {
                            $query->whereNotNull($filter['relationship-field']);
                        }
                    );
                }

                return $builder;
            }

            if (
                array_key_exists('target_type', $filter) &&
                is_string($filter['target_type'])
            ) {
                return $builder->whereHas(
                    $filter['name'],
                    static function ($query) use ($filter) {
                        $query->where(
                            $filter['relationship-field'],
                            $filter['operator'],
                            $filter['value']
                        )->where(
                            'target_type',
                            $filter['target_type']
                        );
                    }
                );
            }

            return $builder->whereHas(
                $filter['name'],
                static function ($query) use ($filter) {
                    $query->where(
                        $filter['relationship-field'],
                        $filter['operator'],
                        $filter['value']
                    );
                }
            );
        }

        if ($filter['value'] === null) {
            if (
                ! array_key_exists('operator', $filter) ||
                empty($filter['operator']) ||
                $filter['operator'] === '='
            ) {
                return $builder->whereNull($filter['name']);
            }

            if (
                array_key_exists('operator', $filter) &&
                (
                    $filter['operator'] === '<>' ||
                    $filter['operator'] === '!='
                )
            ) {
                return $builder->whereNotNull($filter['name']);
            }

            return $builder;
        }

        if (
            isset($filter['operator']) &&
            $filter['operator'] === 'in'
        ) {
            return $builder->whereIn(
                $filter['name'],
                $filter['value']
            );
        }

        // TODO check here if valid field?
        return $builder->where(
            $filter['name'],
            $filter['operator'] ?? '=',
            $filter['value']
        );
    }
}
