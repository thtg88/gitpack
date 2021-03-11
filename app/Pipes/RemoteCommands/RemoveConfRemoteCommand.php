<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class RemoveConfRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        $conf = $traveler->getGitoliteConf();

        return 'rm '.$conf->getConfFilePath();
    }
}
