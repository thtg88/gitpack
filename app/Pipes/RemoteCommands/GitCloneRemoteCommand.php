<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class GitCloneRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        $repository = $traveler->getGitoliteConf()->getRepositoryName();

        return 'git clone git@127.0.0.1:'.$repository;
    }
}
