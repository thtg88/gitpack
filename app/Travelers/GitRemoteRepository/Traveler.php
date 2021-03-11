<?php

namespace App\Travelers\GitRemoteRepository;

use App\GitoliteAdminRepository\Conf;
use App\Travelers\Traveler as BaseTraveler;

abstract class Traveler extends BaseTraveler
{
    protected Conf $conf;

    public function getGitoliteConf(): Conf
    {
        return $this->conf;
    }

    public function getGitoliteConfFilePath(): string
    {
        return $this->conf->getConfFilePath();
    }

    public function setGitoliteConf(Conf $conf): self
    {
        $this->conf = $conf;

        return $this;
    }
}
