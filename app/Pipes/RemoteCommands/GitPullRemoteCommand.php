<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class GitPullRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $_traveler): string
    {
        return 'git pull origin master';
    }
}
