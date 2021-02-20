<?php

namespace App\Repositories;

use App\Models\JournalEntry;

class JournalEntryRepository extends Repository
{
    /** @var string */
    protected static $model_name = 'id';

    /** @var array */
    protected static $order_by_columns = [
        'id' => 'desc',
    ];

    /** @var array */
    protected static $search_columns = [
        //
    ];

    /** @var array */
    protected static $filter_columns = [
        //
    ];

    /**
     * Create a new repository instance.
     *
     * @param \App\Models\JournalEntry $journal_entry
     * @return  void
     */
    public function __construct(JournalEntry $journal_entry)
    {
        $this->model = $journal_entry;

        parent::__construct();
    }
}
