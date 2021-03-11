<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class RemovePublicKeyRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        return 'rm '.$traveler->getGitoliteKeyFilePath();
    }
}
