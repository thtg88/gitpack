<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class ChangeCloneOwnerToGitUserRemoteCommand extends RemoteCommand
{
    use Concerns\PwdTmpFilePathGetter;
    use Concerns\SudoCommandWrapper;

    public function getCommand(Traveler $traveler): string
    {
        $repository = $traveler->getGitoliteConf()->getRepositoryName();

        return $this->sudoWrap(
            'chown git:git /home/git'.$repository,
            $repository
        );
    }
}
