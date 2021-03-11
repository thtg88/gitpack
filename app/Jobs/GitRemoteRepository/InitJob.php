<?php

namespace App\Jobs\GitRemoteRepository;

use App\GitoliteAdminRepository\Conf;
use App\Jobs\SshJob;
use App\Models\App;
use App\Pipelines\InitGitRemoteRepositoryPipeline;
use App\Travelers\GitRemoteRepository\InitTraveler;
use Symfony\Component\Process\Exception\ProcessFailedException;

class InitJob extends SshJob
{
    /**
     * Create a new job instance.
     *
     * @param \App\Models\App
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
        $commands = InitGitRemoteRepositoryPipeline::run($this->getTraveler())
            ->getCommands();

        $process = $this->initSsh($private_key)->execute($commands);
        if (! $process->isSuccessful()) {
            $private_key->flushTmpFile();

            // TODO: should we remove the app if we can't create?

            throw new ProcessFailedException($process);
        }

        $private_key->flushTmpFile();

        // TODO save output
        // $output = $process->getOutput();
        // dd($output);
    }

    protected function getTraveler(): InitTraveler
    {
        $gitolite_conf = new Conf($this->app->name, $this->app->getUserName());

        return (new InitTraveler())->setGitoliteConf($gitolite_conf);
    }
}
