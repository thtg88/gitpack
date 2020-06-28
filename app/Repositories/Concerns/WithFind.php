<?php

namespace App\Repositories\Concerns;

trait WithFind
{
    /**
     * Returns a model from a given id.
     *
     * @param int $id The id of the instance.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id)
    {
        // Assume id as numeric and > 0
        if (empty($id) || ! is_numeric($id)) {
            return null;
        }

        // Get model
        $result = $this->model->where('id', $id);

        $result = $this->withOptionalTrashed($result);

        return $result->first();
    }

    /**
     * Returns the first model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findFirst()
    {
        // Get model
        $result = $this->model->orderBy('id', 'asc');

        $result = $this->withOptionalTrashed($result);

        return $result->first();
    }

    /**
     * Returns the last model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findLast()
    {
        // Get model
        $result = $this->model->orderBy('id', 'desc');

        $result = $this->withOptionalTrashed($result);

        return $result->first();
    }

    /**
     * Returns a model from a given model name.
     *
     * @param mixed $model_name The model name of the instance.
     * @return \Illuminate\Database\Eloquent\Model
     * @TODO expand to include multiple column functionality. Perhaps allow array, with separator additional parameter, or closure.
     */
    public function findByModelName($model_name)
    {
        // Assume id as numeric and > 0
        if (empty($model_name) || ! isset(static::$model_name)) {
            return null;
        }

        // Get model
        $result = $this->model->where(static::$model_name, $model_name);

        $result = $this->withOptionalTrashed($result);

        return $result->first();
    }

    /**
     * Returns a random model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findRandom()
    {
        // Get model
        return $this->model->inRandomOrder()
            ->first();
    }
}
