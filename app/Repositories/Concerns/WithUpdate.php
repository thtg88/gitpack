<?php

namespace App\Repositories\Concerns;

use App\Models\JournalEntry;
use Illuminate\Database\Eloquent\Model;

trait WithUpdate
{
    /**
     * Updates a model instance with given data, from a given id.
     *
     * @param int $id The id of the model
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data)
    {
        // Get model
        $model = $this->find($id);

        if ($model === null) {
            return null;
        }

        // Get rid of unnecessary data e.g. not changed or not in the model
        $data = $this->pruneData($data, $model);

        if (count($data) === 0) {
            // No data changed - No need to fire an update
            return $model;
        }

        // Save data
        $model->fill($data)->save();

        if (config('app.journal_mode') === true) {
            // Create journal entry only if not creating journal entry i.e. infinite recursion
            $journal_entry_classname = JournalEntry::class;

            if ($model instanceof $journal_entry_classname === false) {
                app('JournalEntryHelper')->createJournalEntry(
                    null,
                    $model,
                    $data
                );
            }
        }

        // Get model key name
        $model_key = $model->getKeyName();

        return $this->find($model->{$model_key});
    }

    /**
     * Filter out data that would not change the state of the model.
     *
     * @param array $data The data to set.
     * @param \Illuminate\Database\Eloquent\Model $model The model to update.
     * @param array $exclude A set of columns to exclude from the prune e.g. if the update is meant to update associations as well.
     * @return array
     */
    protected function pruneData(
        array $data,
        Model $model,
        array $exclude = []
    ): array {
        // Get the model fillables
        $model_fillables = $model->getFillable();

        // Get all original model values
        $actual_model_values = array_merge(
            $model->getAttributes(),
            $model->getHidden()
        );

        // Get rid of not original model attributes
        // (e.g. eager-loaded relationships and appended attributes)
        $model_values = array_intersect_key(
            $actual_model_values,
            array_flip($model_fillables)
        );

        // Gets rid of columns that needs to be excluded by the prune
        $data = array_diff_key($data, array_flip($exclude));

        foreach ($data as $column => $value) {
            if (! array_key_exists($column, $model_values)) {
                // If there is no such column in the original model - discard
                unset($data[$column]);
            } elseif ($model_values[$column] == $value) {
                // If the 2 values are the same - discard
                unset($data[$column]);
            }
        }

        return $data;
    }
}
