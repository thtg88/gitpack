<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands\Concerns;

trait PwdTmpFilePathGetter
{
    protected function getPwdTmpFilePath(string $repository): string
    {
        return '~/pwd-'.$repository.'.tmp';
    }
}
