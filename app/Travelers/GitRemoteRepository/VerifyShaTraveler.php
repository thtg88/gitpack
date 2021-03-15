<?php

namespace App\Travelers\GitRemoteRepository;

final class VerifyShaTraveler extends Traveler
{
    private string $sha;
    private string $timestamp;

    public function getSha(): string
    {
        return $this->sha;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function setSha(string $sha): self
    {
        $this->sha = $sha;

        return $this;
    }

    public function setTimestamp(string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
