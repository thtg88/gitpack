<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Collection;

trait WithAllModels
{
    /**
     * Return all the model instances.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        $result = $this->model;

        $result = $this->withOptionalTrashed($result);

        $result = $this->withDefaultOrderBy($result);

        return $result->get();
    }

    /**
     * Return all the model instances in a compact array form:
     * id as index, model_name as value.
     *
     * @return array
     */
    public function allCompact(): array
    {
        $result = $this->model->select('id', static::$model_name);

        $result = $this->withOptionalTrashed($result);

        $result = $this->withDefaultOrderBy($result);

        // Fetch result from DB
        $temp_models = $result->get();

        // Build mapping id => model_name
        $models = [];
        // Get model key name
        $model_key = $this->model->getKeyName();
        foreach ($temp_models as $idx => $model) {
            $models[$model_key] = $model->{static::$model_name};
        }

        return $models;
    }

    /**
     * Return the count of all the model instances.
     *
     * @return int
     */
    public function countAll(): int
    {
        $result = $this->model;

        $result = $this->withOptionalTrashed($result);

        return $result->count();
    }

    /**
     * Return all the model instances' ids.
     *
     * @return array
     */
    public function getAllIds(): array
    {
        $result = $this->model->select('id');

        $result = $this->withOptionalTrashed($result);

        // Order by clause
        $result = $result->orderBy('id');

        return $result->pluck('id')->toArray();
    }
}
