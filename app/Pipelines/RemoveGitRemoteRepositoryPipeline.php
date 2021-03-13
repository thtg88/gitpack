<?php

namespace App\Pipelines;

use App\Pipes\RemoteCommands\CdGitoliteAdminRemoteCommand;
use App\Pipes\RemoteCommands\CreateTmpPwdFileRemoteCommand;
use App\Pipes\RemoteCommands\GitAddModifiedFilesRemoteCommand;
use App\Pipes\RemoteCommands\GitCommitRemoteCommand;
use App\Pipes\RemoteCommands\GitPullRemoteCommand;
use App\Pipes\RemoteCommands\GitPushRemoteCommand;
use App\Pipes\RemoteCommands\RemoveConfRemoteCommand;
use App\Pipes\RemoteCommands\RemoveGitRepositoryRemoteCommand;
use App\Pipes\RemoteCommands\RemoveTmpPwdFileRemoteCommand;
use App\Travelers\GitRemoteRepository\RemoveTraveler;
use Illuminate\Pipeline\Pipeline;

final class RemoveGitRemoteRepositoryPipeline extends Pipeline
{
    protected $pipes = [
        CdGitoliteAdminRemoteCommand::class,
        GitPullRemoteCommand::class,
        RemoveConfRemoteCommand::class,
        // It's necessary to remove the git repo manually from the server
        // (see https://gitolite.com/gitolite/basic-admin.html#removingrenaming-a-repo)
        // So we pipe the password to a txt file (new-line necessary)
        // Sudo remove it, and later remove the tmp txt pwd file
        CreateTmpPwdFileRemoteCommand::class,
        RemoveGitRepositoryRemoteCommand::class,
        RemoveTmpPwdFileRemoteCommand::class,
        GitAddModifiedFilesRemoteCommand::class,
        GitCommitRemoteCommand::class,
        GitPushRemoteCommand::class,
    ];

    public static function run(RemoveTraveler $traveler): RemoveTraveler
    {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
