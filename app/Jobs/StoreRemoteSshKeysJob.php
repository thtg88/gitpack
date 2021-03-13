<?php

namespace App\Jobs;

use App\Actions\StoreRemoteSshKeysAction;
use App\Models\User;

final class StoreRemoteSshKeysJob extends Job
{
    public function __construct(
        private User $user,
        private string $public_key,
    ) {
    }

    public function handle(): void
    {
        $action = new StoreRemoteSshKeysAction($this->user, $this->public_key);

        $action();
    }
}
