<?php

namespace App\Pipelines;

use App\Pipes\RemoteCommands\CdGitoliteAdminRemoteCommand;
use App\Pipes\RemoteCommands\GitAddModifiedFilesRemoteCommand;
use App\Pipes\RemoteCommands\GitCommitRemoteCommand;
use App\Pipes\RemoteCommands\GitPullRemoteCommand;
use App\Pipes\RemoteCommands\GitPushRemoteCommand;
use App\Pipes\RemoteCommands\WriteConfRemoteCommand;
use App\Travelers\GitRemoteRepository\InitTraveler;
use Illuminate\Pipeline\Pipeline;

final class InitGitRemoteRepositoryPipeline extends Pipeline
{
    protected $pipes = [
        CdGitoliteAdminRemoteCommand::class,
        GitPullRemoteCommand::class,
        WriteConfRemoteCommand::class,
        GitAddModifiedFilesRemoteCommand::class,
        GitCommitRemoteCommand::class,
        GitPushRemoteCommand::class,

    ];

    public static function run(InitTraveler $traveler): InitTraveler
    {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
