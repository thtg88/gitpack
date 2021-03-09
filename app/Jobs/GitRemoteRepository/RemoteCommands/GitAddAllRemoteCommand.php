<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\Traveler;

final class GitAddAllRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $_traveler): string
    {
        return 'git add .';
    }
}
