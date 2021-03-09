<?php

namespace App\Jobs\GitRemoteRepository\Pipelines;

use App\Jobs\GitRemoteRepository\RemoteCommands\CdGitoliteAdminRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\CreateTmpPwdFileRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitAddAllRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitCommitRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitPullRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitPushRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\RemoveConfRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\RemoveGitRepositoryRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\RemoveTmpPwdFileRemoteCommand;
use App\Jobs\GitRemoteRepository\Travelers\RemoveTraveler;
use Illuminate\Pipeline\Pipeline;

final class RemovePipeline extends Pipeline
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
        // TODO: Should we add exclusively the new conf?
        GitAddAllRemoteCommand::class,
        GitCommitRemoteCommand::class,
        GitPushRemoteCommand::class,
    ];

    public static function run(RemoveTraveler $traveler): RemoveTraveler
    {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
