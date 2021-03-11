<?php

namespace App\Travelers;

interface TravelerInterface
{
    public function appendCommand(string $command): self;
    public function getCommands(): array;
}
