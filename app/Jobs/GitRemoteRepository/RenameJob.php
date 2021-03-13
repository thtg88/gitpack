<?php

namespace App\Jobs\GitRemoteRepository;

use App\Actions\GitRemoteRepository\RenameAction;
use App\Jobs\Job;
use App\Models\App;

final class RenameJob extends Job
{
    public function __construct(
        private App $app,
        private string $from,
        private string $to,
    ) {
    }

    public function handle(): void
    {
        $action = new RenameAction($this->app, $this->from, $this->to);

        $action();
    }
}
