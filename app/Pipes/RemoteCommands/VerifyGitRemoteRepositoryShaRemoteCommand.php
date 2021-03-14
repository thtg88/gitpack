<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class VerifyGitRemoteRepositoryShaRemoteCommand extends RemoteCommand
{
    use Concerns\PwdTmpFilePathGetter;
    use Concerns\SudoCommandWrapper;

    public function getCommand(Traveler $traveler): string
    {
        $repository = $traveler->getGitoliteConf()->getRepositoryName();

        $sha = $traveler->getSha();

        return $this->sudoWrap(
            'git --git-dir=/home/git/repositories/'.$repository.'.git '.
                'rev-parse --verify '.$sha,
            $repository
        );
    }
}
