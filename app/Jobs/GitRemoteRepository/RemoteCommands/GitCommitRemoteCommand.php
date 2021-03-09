<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\InitTraveler;
use App\Jobs\GitRemoteRepository\Travelers\RemoveTraveler;
use App\Jobs\GitRemoteRepository\Travelers\RenameTraveler;
use App\Jobs\GitRemoteRepository\Travelers\Traveler;
use InvalidArgumentException;

final class GitCommitRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        $conf = $traveler->getGitoliteConf();

        if ($traveler instanceof InitTraveler) {
            return 'git commit -m "Added '.$conf->getConfFilename().'"';
        }

        if ($traveler instanceof RemoveTraveler) {
            return 'git commit -m "Removed '.$conf->getConfFilename().'"';
        }

        if ($traveler instanceof RenameTraveler) {
            $new_conf = $traveler->getNewGitoliteConf();

            return 'git commit -m "Renamed '.$conf->getConfFilename().
                ' to '.$new_conf->getConfFilename().'"';
        }

        throw new InvalidArgumentException(
            'We could not generate a commit message.'
        );
    }
}
