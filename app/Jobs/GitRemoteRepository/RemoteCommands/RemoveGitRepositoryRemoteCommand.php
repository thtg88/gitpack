<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\Traveler;

final class RemoveGitRepositoryRemoteCommand extends RemoteCommand
{
    use Concerns\PwdTmpFilePathGetter;
    use Concerns\SudoCommandWrapper;

    public function getCommand(Traveler $traveler): string
    {
        $conf = $traveler->getGitoliteConf();

        $repository = $conf->getRepositoryName();

        return $this->sudoWrap(
            'rm -rf /home/git/repositories/'.$repository.'.git',
            $repository
        );
    }
}
