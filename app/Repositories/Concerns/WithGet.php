<?php

namespace App\Repositories\Concerns;

use DateTime;
use Illuminate\Database\Eloquent\Collection;

trait WithGet
{
    /**
     * Return all the resources from given ids.
     *
     * @param array $ids The ids of the resources to return.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByIds(array $ids): Collection
    {
        // Filter out empty and non-numeric ids
        $ids = array_filter(
            $ids,
            static function ($id) {
                return is_numeric($id) && ! empty($id);
            }
        );

        // If no ids, return empty set
        if (empty($ids)) {
            return new Collection();
        }

        $result = $this->model->whereIn('id', $ids);

        $result = $this->withOptionalTrashed($result);

        $result = $this->withDefaultOrderBy($result);

        return $result->get();
    }

    /**
     * Return all the resources between a given start and end date.
     * This methods treats the date filter differently depending
     * on the number of columns specified in $date_filter_columns:
     * 0. No date filtering is applied - the result is identical to all()
     * 1. The filter is applied in the form of $start_date <= $date_filter_columns[0] < $end_date
     * 2. The columns are considered to be an interval. Therefore the filter
     * checks if date intervals are overlapping (excluding the edges), in the form:
     * $start_date < $date_filter_columns[0] && $end_date > $date_filter_columns[1]
     * >2. If more than 2 columns are specified, the ones after the second
     * are ignored, and scenario 2 applies.
     *
     * @param \DateTime $start_date The start date.
     * @param \DateTime $end_date The end date.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByStartDateAndEndDate(
        DateTime $start_date,
        DateTime $end_date
    ): Collection {
        $result = $this->model;

        $result = $this->withOptionalTrashed($result);

        // Get total elements of the date filter columns array
        $total_date_filter_columns = count(static::$date_filter_columns);

        switch ($total_date_filter_columns) {
            case 0:
                // Nothing to filter on
                break;
            case 1:
                // The filter is applied in the form of $start_date <= $date_filter_columns[0] < $end_date
                $result = $result->where(
                    static::$date_filter_columns[0],
                    '>=',
                    $start_date->format('Y-m-d H:i:s')
                )->where(
                    static::$date_filter_columns[0],
                    '<',
                    $end_date->format('Y-m-d H:i:s')
                );
                break;
            case 2:
            default:
                // Check if date intervals are overlapping (excluding the edges)
                // $start_date < $date_filter_columns[0] &&
                // $end_date > $date_filter_columns[1]
                $result = $result->where(
                    static::$date_filter_columns[0],
                    '<',
                    $end_date
                )->where(
                    static::$date_filter_columns[1],
                    '>',
                    $start_date
                );
                break;
        }

        $result = $this->withDefaultOrderBy($result);

        return $result->get();
    }

    /**
     * Return the given number of latest inserted model instances.
     *
     * @param int $limit The number of model instances to return.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function latest($limit): Collection
    {
        $result = $this->model;

        $result = $this->withOptionalTrashed($result);

        return $result->latest()
            ->take($limit)
            ->get();
    }
}
