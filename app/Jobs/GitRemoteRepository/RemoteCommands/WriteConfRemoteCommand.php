<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\RenameTraveler;
use App\Jobs\GitRemoteRepository\Travelers\Traveler;

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
