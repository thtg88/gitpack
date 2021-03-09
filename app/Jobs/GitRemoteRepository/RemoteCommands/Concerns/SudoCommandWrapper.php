<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands\Concerns;

trait SudoCommandWrapper
{
    protected function sudoWrap(string $command, string $repository): string
    {
        return 'sudo -S '.$command.' <'.$this->getPwdTmpFilePath($repository);
    }
}
