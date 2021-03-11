<?php

namespace App\Pipes\RemoteCommands\Concerns;

trait PwdTmpFilePathGetter
{
    protected function getPwdTmpFilePath(string $repository): string
    {
        return '~/pwd-'.$repository.'.tmp';
    }
}
