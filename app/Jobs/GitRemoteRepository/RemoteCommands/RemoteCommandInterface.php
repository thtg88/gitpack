<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\Traveler;
use Closure;

interface RemoteCommandInterface
{
    public function handle(Traveler $traveler, Closure $next): mixed;
    public function getCommand(Traveler $traveler): string;
}
