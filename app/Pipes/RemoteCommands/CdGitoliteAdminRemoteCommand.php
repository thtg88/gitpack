<?php

namespace App\Pipes\RemoteCommands;

use App\GitoliteAdminRepository\Utils as GitoliteAdminUtils;
use App\Travelers\Traveler;

final class CdGitoliteAdminRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        return 'cd '.GitoliteAdminUtils::BASE_PATH;
    }
}
