<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\Traveler;

final class RemoveConfRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        $conf = $traveler->getGitoliteConf();

        return 'rm '.$conf->getConfFilePath();
    }
}
