<?php

namespace App\Services\Concerns;

use App\Http\Requests\Contracts\PaginateRequestInterface;
use Illuminate\Pagination\LengthAwarePaginator;

trait WithPagination
{
    /**
     * Return all the model instances.
     *
     * @param \App\Http\Requests\Contracts\PaginateRequestInterface $request
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(PaginateRequestInterface $request): LengthAwarePaginator
    {
        // Get input
        $input = $request->only([
            'page',
            'page_size',
            'recovery',
            'sort_direction',
            'sort_name',
        ]);

        $wheres = [];

        $wheres = array_merge($wheres, $this->getFilterValues($request));

        // Page falls back to 1
        if (! array_key_exists('page', $input) || $input['page'] === null) {
            $input['page'] = 1;
        }

        // Page size fall back to configs
        if (
            ! array_key_exists('page_size', $input) ||
            $input['page_size'] === null
        ) {
            $input['page_size'] = config('app.pagination.page_size');
        }

        $input['q'] = $this->getSearchValue($request);

        if (array_key_exists('recovery', $input) && $input['recovery'] == 1) {
            // Set the repository to also fetch trashed models
            $this->repository = $this->repository->withTrashed();

            $wheres[] = [
                'field' => 'deleted_at',
                'operator' => '<>',
                'value' => null,
            ];
        }

        // Sort name fall back
        if (empty($input['sort_name'])) {
            $input['sort_name'] = null;
        }

        // Sort direction fall back
        if (empty($input['sort_direction'])) {
            $input['sort_direction'] = null;
        }

        // Get paginated resources
        return $this->repository->paginate(
            $input['page_size'],
            $input['page'],
            $input['q'],
            $input['sort_name'],
            $input['sort_direction'],
            $wheres
        );
    }

    /**
     * Return the map of filter values from a given request.
     *
     * @param \App\Http\Requests\Contracts\PaginateRequestInterface $request
     * @return array
     */
    public function getMapFilterValues(PaginateRequestInterface $request): array
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
     * @param \App\Http\Requests\Contracts\PaginateRequestInterface $request
     * @return array
     */
    public function getDefaultFilterValues(PaginateRequestInterface $request): array
    {
        return [];
    }

    /**
     * Return the filter values from a given request.
     *
     * @param \App\Http\Requests\Contracts\PaginateRequestInterface $request
     * @return array
     */
    public function getFilterValues(PaginateRequestInterface $request): array
    {
        $filters = $this->getDefaultFilterValues($request);

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
     * @param \App\Http\Requests\Contracts\PaginateRequestInterface $request
     * @return string|null
     */
    public function getSearchValue(PaginateRequestInterface $request): ?string
    {
        if (empty($request->q) || ! is_string($request->q)) {
            return null;
        }

        return $request->q;
    }

    /**
     * Return the page size value from a given request.
     *
     * @param \App\Http\Requests\Contracts\PaginateRequestInterface $request
     * @return int|null
     */
    public function getPageSize(PaginateRequestInterface $request): int
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
     * @param \App\Http\Requests\Contracts\PaginateRequestInterface $request
     * @return array
     */
    public function getSort(PaginateRequestInterface $request): array
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
     * @param \App\Http\Requests\Contracts\PaginateRequestInterface $request
     * @return array
     */
    public function getPaginationData(PaginateRequestInterface $request): array
    {
        return [
            'filter_map' => $this->getMapFilterValues($request),
            'page_size' => $this->getPageSize($request),
            'search_value' => $this->getSearchValue($request),
            'sort' => $this->getSort($request),
        ];
    }
}
