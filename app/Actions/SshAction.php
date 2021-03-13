<?php

namespace App\Actions;

use App\GitServerPrivateSshKey;
use App\Travelers\Traveler;
use Spatie\Ssh\Ssh;

abstract class SshAction
{
    abstract protected function getTraveler(): Traveler;

    protected function initSshKey(): GitServerPrivateSshKey
    {
        return GitServerPrivateSshKey::createFromContents(
            config('app.git_ssh.private_key')
        )->saveAsTmpFile();
    }

    protected function initSsh(GitServerPrivateSshKey $private_key): Ssh
    {
        return Ssh::create(
            config('app.git_ssh.user'),
            config('app.git_ssh.host'),
        )
            ->usePrivateKey($private_key->getTmpFilename())
            ->disableStrictHostKeyChecking();
    }

    protected function remoteFileExists(
        GitServerPrivateSshKey $private_key,
        string $path
    ): bool {
        return $this->initSsh($private_key)
            ->execute('[ -f '.$path.' ]')
            ->isSuccessful();
    }
}
