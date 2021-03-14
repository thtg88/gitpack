<?php

namespace App\Travelers\GitRemoteRepository;

final class VerifyShaTraveler extends Traveler
{
    private string $sha;

    public function getSha(): string
    {
        return $this->sha;
    }

    public function setSha(string $sha): self
    {
        $this->sha = $sha;

        return $this;
    }
}
