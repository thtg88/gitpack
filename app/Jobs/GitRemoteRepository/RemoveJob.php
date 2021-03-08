<?php

namespace App\Jobs\GitRemoteRepository;

use App\GitoliteRepositoryConfiguration;
use App\Models\App;
use App\SshKey;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RemoveJob extends Job implements SingleGitoliteConfigurationCommandsInterface
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
    public function handle()
    {
        $private_key = $this->initSshKey();
        $gitolite_conf = new GitoliteRepositoryConfiguration(
            $this->app->name,
            $this->app->getUserName(),
        );

        if (! $this->remoteFileExists(
            $private_key,
            $gitolite_conf->getConfFilePath()
        )) {
            return;
        }

        $process = $this->executeCommands($private_key, $gitolite_conf);
        if (! $process->isSuccessful()) {
            $private_key->flushTmpFile();

            throw new ProcessFailedException($process);
        }

        $private_key = $private_key->flushTmpFile();

        // TODO save output
        // $output = $process->getOutput();
        // dd($output);
    }

    public function getCommands(
        GitoliteRepositoryConfiguration $conf
    ): array {
        $repository_name = $conf->getRepositoryName();

        return [
            'rm '.$conf->getConfFilePath(),
            // It's necessary to remove the git repo manually from the server
            // (as indicated in https://gitolite.com/gitolite/basic-admin.html#removingrenaming-a-repo)
            // So we pipe the password to a txt file (new-line necessary)
            // Sudo remove it, and later remove the tmp txt pwd file
            $this->getCreateTmpPwdFileCommand($repository_name),
            $this->getSudoWrappedCommand(
                'rm -rf /home/git/repositories/'.$repository_name.'.git',
                $repository_name
            ),
            $this->getRemoveTmpPwdFileCommand($repository_name),
            'git add .',
            'git commit -m "Removed '.$conf->getConfFilename().'"',
        ];
    }
}
