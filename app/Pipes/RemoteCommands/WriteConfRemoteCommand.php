<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\GitRemoteRepository\RenameTraveler;
use App\Travelers\Traveler;

final class WriteConfRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        if ($traveler instanceof RenameTraveler) {
            $conf = $traveler->getNewGitoliteConf();
        } else {
            $conf = $traveler->getGitoliteConf();
        }

        return 'echo "'.$conf->output().'" > '.$conf->getConfFilePath();
    }
}
