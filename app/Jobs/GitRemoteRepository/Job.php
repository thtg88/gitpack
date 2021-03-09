<?php

namespace App\Jobs\GitRemoteRepository;

use App\Jobs\GitRemoteRepository\Travelers\Traveler;
use App\SshKey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Ssh\Ssh;

abstract class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    abstract protected function getTraveler(): Traveler;

    protected function initSshKey(): SshKey
    {
        return SshKey::createFromContents(config('app.git_ssh.private_key'))
            ->saveAsTmpFile();
    }

    protected function initSsh(SshKey $private_key): Ssh
    {
        return Ssh::create(
            config('app.git_ssh.user'),
            config('app.git_ssh.host'),
        )
            ->usePrivateKey($private_key->getTmpFilename())
            ->disableStrictHostKeyChecking();
    }

    protected function remoteFileExists(
        SshKey $private_key,
        string $path
    ): bool {
        return $this->initSsh($private_key)
            ->execute('[ -f '.$path.' ]')
            ->isSuccessful();
    }
}
