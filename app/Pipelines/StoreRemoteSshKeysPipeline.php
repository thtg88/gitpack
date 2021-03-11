<?php

namespace App\Pipelines;

use App\Pipes\RemoteCommands\CdGitoliteAdminRemoteCommand;
use App\Pipes\RemoteCommands\GitAddModifiedFilesRemoteCommand;
use App\Pipes\RemoteCommands\GitCommitRemoteCommand;
use App\Pipes\RemoteCommands\GitPullRemoteCommand;
use App\Pipes\RemoteCommands\GitPushRemoteCommand;
use App\Pipes\RemoteCommands\WritePublicKeyRemoteCommand;
use App\Travelers\StoreRemoteSshKeysTraveler;
use Illuminate\Pipeline\Pipeline;

final class StoreRemoteSshKeysPipeline extends Pipeline
{
    protected $pipes = [
        CdGitoliteAdminRemoteCommand::class,
        GitPullRemoteCommand::class,
        WritePublicKeyRemoteCommand::class,
        GitAddModifiedFilesRemoteCommand::class,
        GitCommitRemoteCommand::class,
        GitPushRemoteCommand::class,
    ];

    public static function run(
        StoreRemoteSshKeysTraveler $traveler
    ): StoreRemoteSshKeysTraveler {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
