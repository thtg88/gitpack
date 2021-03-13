<?php

namespace App\Jobs\GitRemoteRepository;

use App\Actions\GitRemoteRepository\InitAction;
use App\Jobs\Job;
use App\Models\App;

final class InitJob extends Job
{
    public function __construct(private App $app)
    {
    }

    public function handle(): void
    {
        $action = new InitAction($this->app);

        $action();
    }
}
