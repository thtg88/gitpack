<?php

namespace App\Travelers\GitRemoteRepository;

use App\GitoliteAdminRepository\Conf;

final class RenameTraveler extends Traveler
{
    private Conf $new_conf;

    public function getNewGitoliteConf(): Conf
    {
        return $this->new_conf;
    }

    public function getNewGitoliteConfFilePath(): string
    {
        return $this->new_conf->getConfFilePath();
    }

    public function setNewGitoliteConf(Conf $new_conf): self
    {
        $this->new_conf = $new_conf;

        return $this;
    }
}
