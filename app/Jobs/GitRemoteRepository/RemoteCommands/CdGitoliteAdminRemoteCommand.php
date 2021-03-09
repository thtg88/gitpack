<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\Traveler;

final class CdGitoliteAdminRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        $conf = $traveler->getGitoliteConf();

        return 'cd '.$conf::GITOLITE_ADMIN_PATH;
    }
}
