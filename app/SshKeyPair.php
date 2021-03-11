<?php

namespace App;

use App\Models\User;
use RuntimeException;
use Symfony\Component\Process\Process;

/**
 * This class represents the key pair generated for a user of the application
 * to connect to the Git remote server.
 */
final class SshKeyPair
{
    /**
     * The timestamp at which the key pair has been instantiated.
     *
     * @var int
     */
    private int $time;

    private bool $written_to_disk = false;

    public function __construct(private User $user)
    {
        $this->time = time();
    }

    /**
     * Write the SSH keypair to disk using the native `ssh-keygen` tool,
     * saving the key pair in the root project folder.
     *
     * @return self
     *
     * @throws \RuntimeException If the key has already been written to disk
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException If the keys could not be generated.
     */
    public function writeToDisk(): self
    {
        if ($this->written_to_disk) {
            throw new RuntimeException(
                'The key pair has already been written to disk.'
            );
        }

        $parameters = [
            'ssh-keygen',
            '-C',
            $this->getComment(),
            '-f',
            $this->getPrivateKeyFilename(),
            '-N',
            '',
        ];

        $process = (new Process($parameters))
            ->setWorkingDirectory(base_path());

        $process->mustRun();

        $this->written_to_disk = true;

        return $this;
    }

    /**
     * Return the contents of the private key file.
     *
     * @return string
     *
     * @throws \RuntimeException If the key pair has not been written to disk yet.
     */
    public function getPrivateKeyContents(): string
    {
        if ($this->written_to_disk === false) {
            throw new RuntimeException(
                'The key pair has not been written to disk yet.'
            );
        }

        return file_get_contents($this->getPrivateKeyFilePath());
    }

    /**
     * Return the contents of the public key file.
     *
     * @return string
     *
     * @throws \RuntimeException If the key pair has not been written to disk yet.
     */
    public function getPublicKeyContents(): string
    {
        if ($this->written_to_disk === false) {
            throw new RuntimeException(
                'The key pair has not been written to disk yet.'
            );
        }

        return trim(file_get_contents($this->getPublicKeyFilePath()));
    }


    /**
     * Delete the key pair from disk
     *
     * @return self|null
     */
    public function delete(): ?self
    {
        unlink($this->getPrivateKeyFilePath());
        unlink($this->getPublicKeyFilePath());

        $this->written_to_disk = false;

        return null;
    }

    private function getComment(): string
    {
        return $this->user->email;
    }

    private function getPrivateKeyFilename(): string
    {
        return 'id_rsa_gitpack-'.$this->user->id.'-'.$this->time;
    }

    private function getPrivateKeyFilePath(): string
    {
        return base_path($this->getPrivateKeyFilename());
    }

    private function getPublicKeyFilename(): string
    {
        return $this->getPrivateKeyFilename().'.pub';
    }

    private function getPublicKeyFilePath(): string
    {
        return base_path($this->getPublicKeyFilename());
    }
}
