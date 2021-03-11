<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class RemoveTmpPwdFileRemoteCommand extends RemoteCommand
{
    use Concerns\PwdTmpFilePathGetter;

    public function getCommand(Traveler $traveler): string
    {
        $conf = $traveler->getGitoliteConf();

        $repository = $conf->getRepositoryName();

        return 'rm '.$this->getPwdTmpFilePath($repository);
    }
}
