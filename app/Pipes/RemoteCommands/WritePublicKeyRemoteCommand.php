<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;
use Closure;

final class WritePublicKeyRemoteCommand extends RemoteCommand
{
    public function getCommand(Traveler $traveler): string
    {
        $public_key_contents = $traveler->getPublicKeyContents();
        $key_file_path = $traveler->getGitoliteKeyFilePath();

        return 'echo "'.$public_key_contents.'" > '.$key_file_path;
    }
}
