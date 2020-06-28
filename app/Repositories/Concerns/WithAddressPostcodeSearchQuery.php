<?php

namespace App\Repositories\Concerns;

use DB;

/**
 * Provide an enhanced searchQuery repository method to search for postcode
 * within an address relationship
 */
trait WithAddressPostcodeSearchQuery
{
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

        // We override the behaviour for 'address.postcode' search query
        // To search for a trimmed, space-free, value
        if ($column === 'address.postcode') {
            return $builder->orWhereHas(
                'address',
                static function ($relationship_query) use ($q) {
                    $relationship_query->where(
                        DB::raw('REPLACE(LOWER(postcode), \' \', \'\')'),
                        'LIKE',
                        '%'.str_replace(' ', '', $q).'%'
                    );
                }
            );
        }

        if ($column === 'id') {
            return $builder->orWhere(
                $column,
                'LIKE',
                '%'.$q.'%'
            );
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
