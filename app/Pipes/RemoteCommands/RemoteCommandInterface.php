<?php

namespace App\Pipes\RemoteCommands;

use App\Travelers\Traveler;
use Closure;

interface RemoteCommandInterface
{
    public function handle(Traveler $traveler, Closure $next): mixed;
    public function getCommand(Traveler $traveler): string;
}
