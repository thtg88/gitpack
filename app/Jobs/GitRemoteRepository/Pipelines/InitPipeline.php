<?php

namespace App\Jobs\GitRemoteRepository\Pipelines;

use App\Jobs\GitRemoteRepository\RemoteCommands\CdGitoliteAdminRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitAddAllRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitCommitRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitPullRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\GitPushRemoteCommand;
use App\Jobs\GitRemoteRepository\RemoteCommands\WriteConfRemoteCommand;
use App\Jobs\GitRemoteRepository\Travelers\InitTraveler;
use Illuminate\Pipeline\Pipeline;

final class InitPipeline extends Pipeline
{
    protected $pipes = [
        CdGitoliteAdminRemoteCommand::class,
        GitPullRemoteCommand::class,
        WriteConfRemoteCommand::class,
        // TODO: Should we add exclusively the new conf?
        GitAddAllRemoteCommand::class,
        GitCommitRemoteCommand::class,
        GitPushRemoteCommand::class,

    ];

    public static function run(InitTraveler $traveler): InitTraveler
    {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
