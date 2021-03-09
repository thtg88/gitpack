<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\Traveler;

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
