<?php

namespace App\Repositories;

use DateTime;

interface RepositoryInterface
{
    /**
     * Return the repository model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel();

    /**
     * Return the default columns to filter by the model.
     *
     * @return array
     */
    public function getDefaultFilterColumns();

    /**
     * Return the default columns to order by the repository model.
     *
     * @return array
     */
    public function getDefaultOrderByColumns();

    /**
     * Return the default columns to search by the repository model.
     *
     * @return array
     */
    public function getDefaultSearchColumns();

    /**
     * Return all the model instances.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Return all the model instances in a compact array form:
     * id as index, model_name as value.
     *
     * @return array
     */
    public function allCompact();

    /**
     * Create a new model instance in storage from the given data array.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Deletes a model instance from a given id.
     *
     * @param int $id The id of the model.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function destroy($id);

    /**
     * Returns a model from a given id.
     *
     * @param int $id The id of the instance.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id);

    /**
     * Returns a model from a given model name.
     *
     * @param mixed $model_name The model name of the instance.
     * @return \Illuminate\Database\Eloquent\Model
     * @TODO expand to include multiple column functionality. Perhaps allow array, with separator additional parameter, or closure.
     */
    public function findByModelName($model_name);

    /**
     * Returns the first model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findFirst();

    /**
     * Returns a random model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findRandom();

    /**
     * Return all the model instances' ids.
     *
     * @return array
     */
    public function getAllIds();

    /**
     * Return all the resources from given ids.
     *
     * @param int $ids The ids of the resources to return.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByIds(array $ids);

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
    );

    /**
     * Return the given number of latest inserted model instances.
     *
     * @param int $limit The number of model instances to return.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function latest($limit);

    /**
     * Return the paginated model instances.
     *
     * @param int $page_size The number of model instances to return per page
     * @param int $page The page number
     * @param string $q The optional search query
     * @param string $sort_column The optional sort column
     * @param string $sort_direction The optional sort direction
     * @param array $wheres Additional where clauses
     * @return \Illuminate\Support\Collection
     */
    public function paginate(
        $page_size = 10,
        $page = null,
        $q = null,
        $sort_column = null,
        $sort_direction = null,
        array $wheres = []
    );

    /**
     * Restore a model instance from a given id.
     *
     * @param int $id The id of the model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function restore($id);

    /**
     * Return the model instances matching the given search query.
     *
     * @param string $q The search query.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search($q);

    /**
     * Updates a model instance with given data, from a given id.
     *
     * @param int $id The id of the model
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data);
}
