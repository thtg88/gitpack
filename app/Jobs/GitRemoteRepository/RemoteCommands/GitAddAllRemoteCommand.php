<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\InitTraveler;
use App\Jobs\GitRemoteRepository\Travelers\RemoveTraveler;
use App\Jobs\GitRemoteRepository\Travelers\RenameTraveler;
use App\Jobs\GitRemoteRepository\Travelers\Traveler;
use InvalidArgumentException;

final class GitAddAllRemoteCommand extends RemoteCommand
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

        // TODO should we add all in this case just in case?
        throw new InvalidArgumentException(
            'We could not amend the remote git repository.'
        );
    }
}
