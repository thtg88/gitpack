<?php

namespace App\Jobs\GitRemoteRepository\Travelers;

use App\GitoliteRepositoryConfiguration;
use App\SshKey;

interface TravelerInterface
{
    public function appendCommand(string $command): self;
    public function getCommands(): array;
    public function getGitoliteConf(): GitoliteRepositoryConfiguration;
    public function getGitoliteConfFilePath(): string;
    public function getPrivateKey(): SshKey;
    public function setGitoliteConf(GitoliteRepositoryConfiguration $conf): self;
    public function setPrivateKey(SshKey $private_key): self;
}
