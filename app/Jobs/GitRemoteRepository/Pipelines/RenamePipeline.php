<?php

namespace App\Jobs\GitRemoteRepository\Pipelines;

use App\Jobs\GitRemoteRepository\RemoteCommands\CdGitoliteAdminRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\CreateTmpPwdFileRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitAddAllRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitCommitRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitPullRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitPushRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\RemoveConfRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\RemoveTmpPwdFileRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\RenameGitRepositoryRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\WriteConfRemoteCommand;
use App\Jobs\GitRemoteRepository\Travelers\RenameTraveler;
use Illuminate\Pipeline\Pipeline;

final class RenamePipeline extends Pipeline
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
        GitAddAllRemoteCommand::class,
        GitCommitRemoteCommand::class,
        GitPushRemoteCommand::class,
    ];

    public static function run(RenameTraveler $traveler): RenameTraveler
    {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
