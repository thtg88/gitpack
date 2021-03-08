<?php

namespace App\Jobs\GitRemoteRepository;

use App\GitoliteRepositoryConfiguration;
use App\Models\App;
use Symfony\Component\Process\Exception\ProcessFailedException;

class InitJob extends Job implements SingleGitoliteConfigurationCommandsInterface
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
        $gitolite_conf = new GitoliteRepositoryConfiguration(
            $this->app->name,
            $this->app->getUserName()
        );

        $process = $this->executeCommands($private_key, $gitolite_conf);
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

    public function getCommands(
        GitoliteRepositoryConfiguration $conf
    ): array {
        return [
            'echo "'.$conf->output().'" > '.$conf->getConfFilePath(),
            'git add .',
            'git commit -m "Added '.$conf->getConfFilename().'"',
        ];
    }
}
