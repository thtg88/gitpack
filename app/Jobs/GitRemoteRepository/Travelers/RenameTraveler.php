<?php

namespace App\Jobs\GitRemoteRepository\Travelers;

use App\GitoliteRepositoryConfiguration;

final class RenameTraveler extends Traveler
{
    private GitoliteRepositoryConfiguration $new_conf;

    public function getNewGitoliteConf(): GitoliteRepositoryConfiguration
    {
        return $this->new_conf;
    }

    public function setNewGitoliteConf(GitoliteRepositoryConfiguration $new_conf): self
    {
        $this->new_conf = $new_conf;

        return $this;
    }
}
