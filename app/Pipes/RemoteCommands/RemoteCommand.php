<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;
use Closure;

abstract class RemoteCommand implements RemoteCommandInterface
{
    public function handle(Traveler $traveler, Closure $next): Traveler
    {
        $traveler->appendCommand($this->getCommand($traveler));

        return $next($traveler);
    }

    abstract public function getCommand(Traveler $traveler): string;
}
