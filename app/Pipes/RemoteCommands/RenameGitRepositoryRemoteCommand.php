<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;

final class RenameGitRepositoryRemoteCommand extends RemoteCommand
{
    use Concerns\PwdTmpFilePathGetter;
    use Concerns\SudoCommandWrapper;

    public function getCommand(Traveler $traveler): string
    {
        $old_conf = $traveler->getGitoliteConf();
        $new_conf = $traveler->getNewGitoliteConf();

        $old_repository = $old_conf->getRepositoryName();
        $new_repository = $new_conf->getRepositoryName();

        return $this->sudoWrap(
            'mv /home/git/repositories/'.$old_repository.'.git'.
                ' /home/git/repositories/'.$new_repository.'.git',
            $old_repository
        );
    }
}
