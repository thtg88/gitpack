<?php

namespace App\Travelers;

use App\GitoliteAdminRepository\Key;

final class StoreRemoteSshKeysTraveler extends Traveler
{
    private Key $key;

    public function getGitoliteKeyFilePath(): string
    {
        return $this->key->getFilePath();
    }

    public function getKey(): Key
    {
        return $this->key;
    }

    public function getPublicKeyContents(): string
    {
        return $this->key->getPublicKeyContents();
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
