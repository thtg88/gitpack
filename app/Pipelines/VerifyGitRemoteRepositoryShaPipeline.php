<?php

namespace App\Pipelines;

use App\Pipes\RemoteCommands\CatGitRemoteRepositoryFileRemoteCommand;
use App\Pipes\RemoteCommands\CreateTmpPwdFileRemoteCommand;
use App\Pipes\RemoteCommands\RemoveTmpPwdFileRemoteCommand;
use App\Pipes\RemoteCommands\VerifyGitRemoteRepositoryShaRemoteCommand;
use App\Travelers\GitRemoteRepository\VerifyShaTraveler;
use Illuminate\Pipeline\Pipeline;

final class VerifyGitRemoteRepositoryShaPipeline extends Pipeline
{
    /**
     * The array of class pipes.
     *
     * As we need to consult the git repo (under the `git` user)
     * from the server, we pipe the password to a tmp file
     * (new-line necessary), sudo remove it, and later remove the tmp file.
     *
     * @var array
     */
    protected $pipes = [
        CreateTmpPwdFileRemoteCommand::class,
        VerifyGitRemoteRepositoryShaRemoteCommand::class,
        CatGitRemoteRepositoryFileRemoteCommand::class,
        RemoveTmpPwdFileRemoteCommand::class,
    ];

    public static function run(VerifyShaTraveler $traveler): VerifyShaTraveler
    {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
