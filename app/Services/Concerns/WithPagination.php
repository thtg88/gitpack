<?php

namespace App\Services\Concerns;

use App\Http\Requests\Contracts\IndexRequestInterface;

trait WithPagination
{
    /**
     * Return the map of filter values from a given request.
     *
     * @param \App\Http\Requests\Contracts\IndexRequestInterface $request
     * @return array
     */
    public function getMapFilterValues(IndexRequestInterface $request): array
    {
        $filters = $this->getFilterValues($request);

        return array_reduce(
            $filters,
            static function ($result, $filter) {
                $key = $filter['name'];
                $data = [
                    'operator' => $filter['operator'],
                    'value' => $filter['value'],
                ];
                if (array_key_exists('relationship-field', $filter)) {
                    $key .= '.'.$filter['relationship-field'];

                    $data['relationship-field'] = $filter['relationship-field'];
                }
                if (array_key_exists('target_type', $filter)) {
                    $key .= '.'.$filter['target_type'];

                    $data['target_type'] = $filter['target_type'];
                }

                $result[$key] = $data;

                return $result;
            },
            []
        );
    }

    /**
     * Return the default filter values.
     *
     * @return array
     */
    public function getDefaultFilterValues(): array
    {
        return [
            //
        ];
    }

    /**
     * Return the filter values from a given request.
     *
     * @param \App\Http\Requests\Contracts\IndexRequestInterface $request
     * @return array
     */
    public function getFilterValues(IndexRequestInterface $request): array
    {
        $filters = $this->getDefaultFilterValues();

        if (! is_array($request->filters)) {
            return $filters;
        }

        // No need to check for other constraints
        // as checked in PaginateRequest

        foreach ($request->filters as $filter) {
            // Remove existing filters with the same name and operator
            $filters = array_filter(
                $filters,
                static function ($_filter) use ($filter) {
                    return (
                        $filter['name'] !== $_filter['name'] ||
                        $filter['operator'] !== $_filter['operator']
                    ) && (
                        $filter['name'] !== $_filter['name'] ||
                        $filter['value'] !== $_filter['value']
                    );
                }
            );

            $filter_data = [
                'name' => $filter['name'],
                'operator' => $filter['operator'],
                'value' => $filter['value'],
            ];
            if (array_key_exists('relationship-field', $filter)) {
                $filter_data['relationship-field'] = $filter['relationship-field'];
            }
            if (array_key_exists('target_type', $filter)) {
                $filter_data['target_type'] = $filter['target_type'];
            }

            $filters[] = $filter_data;
        }

        return $filters;
    }

    /**
     * Return the search value from a given request.
     *
     * @param \App\Http\Requests\Contracts\IndexRequestInterface $request
     * @return string|null
     */
    public function getSearchValue(IndexRequestInterface $request): ?string
    {
        if (empty($request->q) || ! is_string($request->q)) {
            return null;
        }

        return $request->q;
    }

    /**
     * Return the page size value from a given request.
     *
     * @param \App\Http\Requests\Contracts\IndexRequestInterface $request
     * @return int|null
     */
    public function getPageSize(IndexRequestInterface $request): int
    {
        if (
            empty($request->page_size) ||
            filter_var($request->page_size, FILTER_VALIDATE_INT) === false
        ) {
            return null;
        }

        return $request->page_size;
    }

    /**
     * Return the sort from a given request.
     *
     * @param \App\Http\Requests\Contracts\IndexRequestInterface $request
     * @return array
     */
    public function getSort(IndexRequestInterface $request): array
    {
        $default_order_by_columns = $this->repository
            ->getDefaultOrderByColumns();

        // If no order by columns, sort by id asc
        if (count($default_order_by_columns) === 0) {
            return [
                'direction' => 'asc',
                'name' => 'id',
            ];
        }

        // If no valid sort name and direction provided, sorted by default
        if (
            empty($request->sort_name) ||
            ! is_string($request->sort_name) ||
            empty($request->sort_direction) ||
            ! is_string($request->sort_direction)
        ) {
            return [
                'direction' => array_values($default_order_by_columns)[0],
                'name' => array_keys($default_order_by_columns)[0],
            ];
        }

        return [
            'direction' => $request->sort_direction,
            'name' => $request->sort_name,
        ];
    }

    /**
     * Return the pagination data from a given request.
     *
     * @param \App\Http\Requests\Contracts\IndexRequestInterface $request
     * @return array
     */
    public function getPaginationData(IndexRequestInterface $request): array
    {
        return [
            'filter_map' => $this->getMapFilterValues($request),
            'page_size' => $this->getPageSize($request),
            'search_value' => $this->getSearchValue($request),
            'sort' => $this->getSort($request),
        ];
    }
}
