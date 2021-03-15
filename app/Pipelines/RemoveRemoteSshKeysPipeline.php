<?php

namespace App\Pipelines;

use App\Pipes\RemoteCommands\CdGitoliteAdminRemoteCommand;
use App\Pipes\RemoteCommands\GitAddModifiedFilesRemoteCommand;
use App\Pipes\RemoteCommands\GitCommitRemoteCommand;
use App\Pipes\RemoteCommands\GitPullRemoteCommand;
use App\Pipes\RemoteCommands\GitPushRemoteCommand;
use App\Pipes\RemoteCommands\RemovePublicKeyRemoteCommand;
use App\Travelers\RemoteSshKeys\RemoveTraveler;
use Illuminate\Pipeline\Pipeline;

final class RemoveRemoteSshKeysPipeline extends Pipeline
{
    protected $pipes = [
        CdGitoliteAdminRemoteCommand::class,
        GitPullRemoteCommand::class,
        RemovePublicKeyRemoteCommand::class,
        GitAddModifiedFilesRemoteCommand::class,
        GitCommitRemoteCommand::class,
        GitPushRemoteCommand::class,
        // TODO: remove repository copy
    ];

    public static function run(
        RemoveTraveler $traveler
    ): RemoveTraveler {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
