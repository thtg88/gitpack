<?php

namespace App\Jobs\GitRemoteRepository;

use App\Jobs\GitRemoteRepository\Pipelines\RemovePipeline;
use App\Jobs\GitRemoteRepository\Travelers\RemoveTraveler;
use App\GitoliteAdminRepository\Conf;
use App\Models\App;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RemoveJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @param \App\Models\App $app
     * @return void
     */
    public function __construct(private App $app)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $private_key = $this->initSshKey();
        $traveler = RemovePipeline::run($this->getTraveler());
        $conf_path = $traveler->getGitoliteConfFilePath();

        if (! $this->remoteFileExists($private_key, $conf_path)) {
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

    protected function getTraveler(): RemoveTraveler
    {
        $gitolite_conf = new Conf($this->app->name, $this->app->getUserName());

        return (new RemoveTraveler())->setGitoliteConf($gitolite_conf);
    }
}
