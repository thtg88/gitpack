<?php

namespace App\Jobs\GitRemoteRepository;

use App\GitoliteRepositoryConfiguration;
use App\Jobs\GitRemoteRepository\Pipelines\RenamePipeline;
use App\Jobs\GitRemoteRepository\Travelers\RenameTraveler;
use App\Models\App;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RenameJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @param \App\Models\App $app
     * @param string $from
     * @param string $to
     * @return void
     */
    public function __construct(
        private App $app,
        private string $from,
        private string $to,
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $private_key = $this->initSshKey();
        $traveler = RenamePipeline::run($this->getTraveler());
        $old_conf_path = $traveler->getGitoliteConfFilePath();

        if (! $this->remoteFileExists($private_key, $old_conf_path)) {
            return;
        }

        $process = $this->initSsh($private_key)
            ->execute($traveler->getCommands());
        if (! $process->isSuccessful()) {
            $private_key->flushTmpFile();

            throw new ProcessFailedException($process);
        }

        $private_key = $private_key->flushTmpFile();

        // TODO save output
        // $output = $process->getOutput();
        // dd($output);
    }

    public function getTraveler(): RenameTraveler
    {
        $old_conf = new GitoliteRepositoryConfiguration(
            $this->from,
            $this->app->getUserName(),
        );
        $new_conf = new GitoliteRepositoryConfiguration(
            $this->to,
            $this->app->getUserName(),
        );

        return (new RenameTraveler())->setGitoliteConf($old_conf)
            ->setNewGitoliteConf($new_conf);
    }
}
