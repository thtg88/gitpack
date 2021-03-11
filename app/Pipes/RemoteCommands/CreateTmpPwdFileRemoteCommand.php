<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class CreateTmpPwdFileRemoteCommand extends RemoteCommand
{
    use Concerns\PwdTmpFilePathGetter;

    public function getCommand(Traveler $traveler): string
    {
        $conf = $traveler->getGitoliteConf();

        $repository = $conf->getRepositoryName();

        return "echo \"".config('app.git_ssh.sudo_password')."\n\"".
            " > ".$this->getPwdTmpFilePath($repository);
    }
}
