<?php

namespace App\Pipelines;

use App\Pipes\RemoteCommands\CdGitoliteAdminRemoteCommand;
use App\Pipes\RemoteCommands\CreateTmpPwdFileRemoteCommand;
use App\Pipes\RemoteCommands\GitAddModifiedFilesRemoteCommand;
use App\Pipes\RemoteCommands\GitCommitRemoteCommand;
use App\Pipes\RemoteCommands\GitPullRemoteCommand;
use App\Pipes\RemoteCommands\GitPushRemoteCommand;
use App\Pipes\RemoteCommands\RemoveConfRemoteCommand;
use App\Pipes\RemoteCommands\RemoveTmpPwdFileRemoteCommand;
use App\Pipes\RemoteCommands\RenameGitRepositoryRemoteCommand;
use App\Pipes\RemoteCommands\WriteConfRemoteCommand;
use App\Travelers\GitRemoteRepository\RenameTraveler;
use Illuminate\Pipeline\Pipeline;

final class RenameGitRemoteRepositoryPipeline extends Pipeline
{
    protected $pipes = [
        CdGitoliteAdminRemoteCommand::class,
        GitPullRemoteCommand::class,
        // It's necessary to rename the git repo manually from the server
        // (as indicated in https://gitolite.com/gitolite/basic-admin.html#removingrenaming-a-repo)
        // So we pipe the password to a txt file (new-line necessary)
        // Sudo move it, and later remove the tmp txt pwd file
        CreateTmpPwdFileRemoteCommand::class,
        RenameGitRepositoryRemoteCommand::class,
        RemoveTmpPwdFileRemoteCommand::class,
        RemoveConfRemoteCommand::class,
        WriteConfRemoteCommand::class,
        GitAddModifiedFilesRemoteCommand::class,
        GitCommitRemoteCommand::class,
        GitPushRemoteCommand::class,
    ];

    public static function run(RenameTraveler $traveler): RenameTraveler
    {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
