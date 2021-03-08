<?php

namespace App\Jobs\GitRemoteRepository;

use App\GitoliteRepositoryConfiguration;

interface SingleGitoliteConfigurationCommandsInterface
{
    public function getCommands(
        GitoliteRepositoryConfiguration $conf
    ): array;
}
