<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class GitPushRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $_traveler): string
    {
        return 'git push origin master';
    }
}
