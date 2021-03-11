<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\GitRemoteRepository\InitTraveler;
use App\Travelers\GitRemoteRepository\RemoveTraveler;
use App\Travelers\GitRemoteRepository\RenameTraveler;
use App\Travelers\StoreRemoteSshKeysTraveler;
use App\Travelers\Traveler;
use InvalidArgumentException;

final class GitAddModifiedFilesRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        return 'git add '.implode(' ', $this->getAddedFiles($traveler));
    }

    public function getAddedFiles(Traveler $traveler): array
    {
        if ($traveler instanceof InitTraveler) {
            return [$traveler->getGitoliteConfFilePath()];
        }

        if ($traveler instanceof RemoveTraveler) {
            return [$traveler->getGitoliteConfFilePath()];
        }

        if ($traveler instanceof RenameTraveler) {
            return [
                $traveler->getGitoliteConfFilePath(),
                $traveler->getNewGitoliteConfFilePath(),
            ];
        }

        if ($traveler instanceof StoreRemoteSshKeysTraveler) {
            return [$traveler->getGitoliteKeyFilePath()];
        }

        // TODO should we add all in this case just in case?
        throw new InvalidArgumentException(
            'We could not amend the remote git repository.'
        );
    }
}
