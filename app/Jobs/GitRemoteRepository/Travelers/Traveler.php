<?php

namespace App\Jobs\GitRemoteRepository\Travelers;

use App\GitoliteRepositoryConfiguration;
use App\SshKey;

abstract class Traveler implements TravelerInterface
{
    protected array $commands = [];
    protected GitoliteRepositoryConfiguration $conf;
    protected SshKey $private_key;

    public function appendCommand(string $command): self
    {
        $this->commands[] = $command;

        return $this;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getGitoliteConf(): GitoliteRepositoryConfiguration
    {
        return $this->conf;
    }

    public function getGitoliteConfFilePath(): string
    {
        return $this->conf->getConfFilePath();
    }

    public function getPrivateKey(): SshKey
    {
        return $this->private_key;
    }

    public function setGitoliteConf(GitoliteRepositoryConfiguration $conf): self
    {
        $this->conf = $conf;

        return $this;
    }

    public function setPrivateKey(SshKey $private_key): self
    {
        $this->private_key = $private_key;

        return $this;
    }
}
