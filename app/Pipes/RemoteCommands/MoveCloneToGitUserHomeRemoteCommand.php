<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class MoveCloneToGitUserHomeRemoteCommand extends RemoteCommand
{
    use Concerns\PwdTmpFilePathGetter;
    use Concerns\SudoCommandWrapper;

    public function getCommand(Traveler $traveler): string
    {
        $repository = $traveler->getGitoliteConf()->getRepositoryName();

        return $this->sudoWrap(
            'mv ~/'.$repository.' /home/git/'.$repository,
            $repository
        );
    }
}
