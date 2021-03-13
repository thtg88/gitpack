<?php

namespace App\Jobs\GitRemoteRepository;

use App\Actions\GitRemoteRepository\RemoveAction;
use App\Jobs\Job;
use App\Models\App;

final class RemoveJob extends Job
{
    public function __construct(private App $app)
    {
    }

    public function handle(): void
    {
        $action = new RemoveAction($this->app);

        $action();
    }
}
