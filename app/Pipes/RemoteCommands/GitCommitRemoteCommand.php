<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\GitRemoteRepository\InitTraveler as InitGitRemoteRepositoryTraveler;
use App\Travelers\GitRemoteRepository\RemoveTraveler as RemoveGitRemoteRepositoryTraveler;
use App\Travelers\GitRemoteRepository\RenameTraveler as RenameGitRemoteRepositoryTraveler;
use App\Travelers\RemoteSshKeys\RemoveTraveler as RemoveRemoteSshKeysTraveler;
use App\Travelers\RemoteSshKeys\StoreTraveler as StoreRemoteSshKeysTraveler;
use App\Travelers\Traveler;
use InvalidArgumentException;

final class GitCommitRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        if ($traveler instanceof RemoveRemoteSshKeysTraveler) {
            $key = $traveler->getKey();

            return 'git commit -m "Removed '.$key->getFilename().'"';
        }

        if ($traveler instanceof StoreRemoteSshKeysTraveler) {
            $key = $traveler->getKey();

            return 'git commit -m "Added '.$key->getFilename().'"';
        }

        if ($traveler instanceof InitGitRemoteRepositoryTraveler) {
            $conf = $traveler->getGitoliteConf();

            return 'git commit -m "Added '.$conf->getConfFilename().'"';
        }

        if ($traveler instanceof RemoveGitRemoteRepositoryTraveler) {
            $conf = $traveler->getGitoliteConf();

            return 'git commit -m "Removed '.$conf->getConfFilename().'"';
        }

        if ($traveler instanceof RenameGitRemoteRepositoryTraveler) {
            $conf = $traveler->getGitoliteConf();
            $new_conf = $traveler->getNewGitoliteConf();

            return 'git commit -m "Renamed '.$conf->getConfFilename().
                ' to '.$new_conf->getConfFilename().'"';
        }

        throw new InvalidArgumentException(
            'We could not generate a commit message.'
        );
    }
}
