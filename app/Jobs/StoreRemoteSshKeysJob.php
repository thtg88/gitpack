<?php

namespace App\Jobs;

use App\GitoliteAdminRepository\Key;
use App\Models\User;
use App\Pipelines\StoreRemoteSshKeysPipeline;
use App\Travelers\RemoteSshKeys\StoreTraveler;
use phpseclib3\Crypt\RSA\PublicKey;
use Symfony\Component\Process\Exception\ProcessFailedException;

class StoreRemoteSshKeysJob extends SshJob
{
    /**
     * Create a new job instance.
     *
     * @param \App\Models\User $user
     * @param \phpseclib3\Crypt\RSA\PublicKey $public_key
     * @return void
     */
    public function __construct(
        private User $user,
        private PublicKey $public_key,
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $admin_private_key = $this->initSshKey();
        $traveler = StoreRemoteSshKeysPipeline::run($this->getTraveler());
        $key_path = $traveler->getGitoliteKeyFilePath();

        // if key already exist remove old one
        if ($this->remoteFileExists($admin_private_key, $key_path)) {
            $remover_traveler = RemoveRemoteSshKeysPipeline::run(
                $this->getRemoverTraveler()
            );
            $remover = $this->initSsh($admin_private_key)
                ->execute($remover_traveler->getCommands());
            if (! $remover->isSuccessful()) {
                throw new ProcessFailedException($remover);
            }
        }

        // add the new keys
        $process = $this->initSsh($admin_private_key)
            ->execute($traveler->getCommands());
        if (! $process->isSuccessful()) {
            $admin_private_key->flushTmpFile();

            throw new ProcessFailedException($process);
        }

        $admin_private_key = $admin_private_key->flushTmpFile();

        // TODO save output
        // $output = $process->getOutput();
        // dd($output);
    }

    protected function getRemoverTraveler(): RemoveRemoteSshKeysTraveler
    {
        $key = new Key($this->user, $this->public_key);

        return (new RemoveRemoteSshKeysTraveler())->setKey($key);
    }

    protected function getTraveler(): StoreTraveler
    {
        $key = new Key($this->user, $this->public_key);

        return (new StoreTraveler())->setKey($key);
    }
}
