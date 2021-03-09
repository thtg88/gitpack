<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\Traveler;

final class GitPushRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $_traveler): string
    {
        return 'git push origin master';
    }
}
