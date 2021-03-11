<?php

namespace App\Travelers\RemoteSshKeys;

use App\GitoliteAdminRepository\Key;

final class StoreTraveler extends Traveler
{
    public function getPublicKeyContents(): string
    {
        return $this->key->getPublicKeyContents();
    }
}
