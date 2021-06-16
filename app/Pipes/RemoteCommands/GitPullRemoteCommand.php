<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class GitPullRemoteCommand extends RemoteCommand
{
    /** @psalm-suppress ParamNameMismatch */
    public function getCommand(Traveler $_traveler): string
    {
        return 'git pull origin master';
    }
}
