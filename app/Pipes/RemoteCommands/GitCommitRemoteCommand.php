<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\GitRemoteRepository\InitTraveler;
use App\Travelers\GitRemoteRepository\RemoveTraveler;
use App\Travelers\GitRemoteRepository\RenameTraveler;
use App\Travelers\StoreRemoteSshKeysTraveler;
use App\Travelers\Traveler;
use InvalidArgumentException;

final class GitCommitRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        if ($traveler instanceof StoreRemoteSshKeysTraveler) {
            $key = $traveler->getKey();

            return 'git commit -m "Added '.$key->getFilename().'"';
        }

        if ($traveler instanceof InitTraveler) {
            $conf = $traveler->getGitoliteConf();

            return 'git commit -m "Added '.$conf->getConfFilename().'"';
        }

        if ($traveler instanceof RemoveTraveler) {
            $conf = $traveler->getGitoliteConf();

            return 'git commit -m "Removed '.$conf->getConfFilename().'"';
        }

        if ($traveler instanceof RenameTraveler) {
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
