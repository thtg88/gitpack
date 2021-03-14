<?php

namespace App\Actions;

use App\GitServer\PrivateSshKey;
use App\Travelers\Traveler;
use Spatie\Ssh\Ssh;

abstract class SshAction
{
    abstract protected function getTraveler(): Traveler;

    protected function initSshKey(): PrivateSshKey
    {
        return PrivateSshKey::createFromContents(
            config('app.git_ssh.private_key')
        )->saveAsTmpFile();
    }

    protected function initSsh(PrivateSshKey $private_key): Ssh
    {
        return Ssh::create(
            config('app.git_ssh.user'),
            config('app.git_ssh.host'),
        )
            ->usePrivateKey($private_key->getTmpFilename())
            ->disableStrictHostKeyChecking();
    }

    protected function remoteFileExists(PrivateSshKey $key, string $path): bool
    {
        return $this->initSsh($key)
            ->execute('[ -f '.$path.' ]')
            ->isSuccessful();
    }
}
