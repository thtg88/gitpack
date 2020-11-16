<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

trait WithSearch
{
    /**
     * Return the model instances matching the given search query.
     *
     * @param string $q The search query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($q): Collection
    {
        // If empty query or no search columns provided
        if ($q === null || $q === '') {
            // Return empty collection
            return new Collection();
        }

        // If no search columns provided
        if (
            ! is_array(static::$search_columns) ||
            count(static::$search_columns) <= 0
        ) {
            // Return empty collection
            return new Collection();
        }

        $result = $this->model;

        $result = $this->withOptionalTrashed($result);

        // Search clause
        $result = $this->searchQuery($result, $q);

        // Order by clause
        $result = $this->withDefaultOrderBy($result);

        // Limit search results to 1000
        return $result->take(1000)->get();
    }

    /**
     * Return a query builder that builds an SQL search,
     * from a given existing query builder, and a search query string.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model $builder
     * @param string $q
     * @return \Illuminate\Database\Query\Builder
     */
    protected function searchQuery($builder, $q)
    {
        // Perform lowercase search to make it case insensitive
        $q = strtolower($q);

        return $builder->where(function ($query) use ($q) {
            foreach (static::$search_columns as $idx => $column) {
                $query = $this->searchQueryColumn($query, $q, $column);
            }
        });
    }

    /**
     * Return a query builder that builds an SQL search,
     * from a given existing query builder, a search query string, and a column.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model $builder
     * @param string $q
     * @param string $column
     * @return \Illuminate\Database\Query\Builder
     */
    protected function searchQueryColumn($builder, $q, $column)
    {
        if (! is_string($column)) {
            return $builder;
        }

        if ($column === 'id') {
            return $builder->orWhere($column, 'LIKE', '%'.$q.'%');
        }

        // How many times is the dot ('.') in the string
        // (denoting relationship)
        $column_dot_count = substr_count($column, '.');

        // Ignore string that has 2 or more dots ('.') in it
        // As we are using a single-dotted string
        // to perform research on direct relationship
        if ($column_dot_count > 1) {
            return $builder;
        }

        // Perform search on model's column
        if ($column_dot_count === 0) {
            return $builder->orWhere(
                DB::raw('LOWER('.$column.')'),
                'LIKE',
                '%'.$q.'%'
            );
        }

        // Perform search on direct relationship of model

        // We know $column will have exactly 1 string before
        // and 1 after the '.'
        [$relationship, $relationship_column] = explode('.', $column);

        return $builder->orWhereHas(
            $relationship,
            static function ($relationship_query) use ($q, $relationship_column) {
                $relationship_query->where(
                    DB::raw('LOWER('.$relationship_column.')'),
                    'LIKE',
                    '%'.$q.'%'
                );
            }
        );
    }
}
