<?php

namespace App\Travelers\RemoteSshKeys;

use App\GitoliteAdminRepository\Key;
use App\Travelers\Traveler as BaseTraveler;

abstract class Traveler extends BaseTraveler
{
    protected Key $key;

    public function getGitoliteKeyFilePath(): string
    {
        return $this->key->getFilePath();
    }

    public function getKey(): Key
    {
        return $this->key;
    }

    public function getUserName(): string
    {
        return $this->key->getUserName();
    }

    public function setKey(Key $key): self
    {
        $this->key = $key;

        return $this;
    }
}
