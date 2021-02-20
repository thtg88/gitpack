<?php

namespace App\Helpers;

use App\Models\JournalEntry;
use App\Repositories\JournalEntryRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class JournalEntryHelper
{
    /**
     * The journal entry repo implementation.
     *
     * @var \App\Repositories\JournalEntryRepository
     */
    protected $journal_entries;

    /**
     * Create a new helper instance.
     *
     * @param \App\Repositories\JournalEntryRepository $journal_entries
     * @return void
     */
    public function __construct(JournalEntryRepository $journal_entries)
    {
        $this->journal_entries = $journal_entries;
    }

    /**
     * Create a new journal entry instance in storage.
     *
     * @param string $action The action performing while creating the entry.
     * @param \Illuminate\Database\Eloquent\Model|null $model The model the action is performed on.
     * @param array $content The action content data.
     * @return \App\Models\JournalEntry
     */
    public function createJournalEntry(
        $action,
        ?Model $model,
        array $content = null
    ): JournalEntry {
        if ($model !== null) {
            // Get model class name
            $class_name = get_class($model);

            // Get morph map
            $morph_map = Relation::morphMap();

            // Get target table for model
            $target_table = array_search($class_name, $morph_map);
            if ($target_table === false) {
                $target_table = null;
            }

            $id = $model->id;
        } else {
            $target_table = null;
            $id = null;
        }

        // Get current authenticated user
        $user = auth()->user();

        // Build data array to save journal entry
        $data = [
            'target_id' => $id,
            'target_table' => $target_table,
            'action' => $action,
        ];

        if ($user !== null) {
            $data['user_id'] = $user->id;
        }

        if ($content === null) {
            $data['content'] = null;
        } else {
            // Remove hidden attributes from being posted in the journals (e.g. password)
            $content = $model === null ?
                $content :
                array_diff_key($content, array_flip($model->getHidden()));

            $data['content'] = json_encode($content);
        }

        return $this->journal_entries->create($data);
    }
}
