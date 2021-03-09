<?php

namespace App\Jobs\GitRemoteRepository\RemoteCommands;

use App\Jobs\GitRemoteRepository\Travelers\Traveler;
use Closure;

abstract class RemoteCommand implements RemoteCommandInterface
{
    public function handle(Traveler $traveler, Closure $next): mixed
    {
        $traveler->appendCommand($this->getCommand($traveler));

        $response = $next($traveler);

        dump($response);

        return $response;
    }

    abstract public function getCommand(Traveler $traveler): string;
}
