<?php

namespace App\Travelers;

use App\GitoliteAdminRepository\Conf;

abstract class Traveler implements TravelerInterface
{
    protected array $commands = [];

    public function appendCommand(string $command): self
    {
        $this->commands[] = $command;

        return $this;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
}
