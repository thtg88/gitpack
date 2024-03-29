<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\GitRemoteRepository\InitTraveler as InitGitRemoteRepositoryTraveler;
use App\Travelers\GitRemoteRepository\RemoveTraveler as RemoveGitRemoteRepositoryTraveler;
use App\Travelers\GitRemoteRepository\RenameTraveler as RenameGitRemoteRepositoryTraveler;
use App\Travelers\RemoteSshKeys\RemoveTraveler as RemoveRemoteSshKeysTraveler;
use App\Travelers\RemoteSshKeys\StoreTraveler as StoreRemoteSshKeysTraveler;
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
        if ($traveler instanceof InitGitRemoteRepositoryTraveler) {
            return [$traveler->getGitoliteConfFilePath()];
        }

        if ($traveler instanceof RemoveGitRemoteRepositoryTraveler) {
            return [$traveler->getGitoliteConfFilePath()];
        }

        if ($traveler instanceof RenameGitRemoteRepositoryTraveler) {
            return [
                $traveler->getGitoliteConfFilePath(),
                $traveler->getNewGitoliteConfFilePath(),
            ];
        }

        if ($traveler instanceof RemoveRemoteSshKeysTraveler) {
            return [$traveler->getGitoliteKeyFilePath()];
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
