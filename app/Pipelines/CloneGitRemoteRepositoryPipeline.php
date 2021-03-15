<?php

namespace App\Pipelines;

use App\Pipes\RemoteCommands\ChangeCloneOwnerToGitUserRemoteCommand;
use App\Pipes\RemoteCommands\CreateTmpPwdFileRemoteCommand;
use App\Pipes\RemoteCommands\GitCloneRemoteCommand;
use App\Pipes\RemoteCommands\MoveCloneToGitUserHomeRemoteCommand;
use App\Pipes\RemoteCommands\RemoveTmpPwdFileRemoteCommand;
use App\Travelers\GitRemoteRepository\CloneTraveler;
use Illuminate\Pipeline\Pipeline;

final class CloneGitRemoteRepositoryPipeline extends Pipeline
{
    protected $pipes = [
        // Pipe the password to a txt file (new-line necessary)
        // Sudo move it, and later remove the tmp txt pwd file
        CreateTmpPwdFileRemoteCommand::class,
        GitCloneRemoteCommand::class,
        MoveCloneToGitUserHomeRemoteCommand::class,
        ChangeCloneOwnerToGitUserRemoteCommand::class,
        RemoveTmpPwdFileRemoteCommand::class,
    ];

    public static function run(CloneTraveler $traveler): CloneTraveler
    {
        return app(self::class)->send($traveler)->thenReturn();
    }
}
