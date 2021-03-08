<?php

namespace App\Jobs\GitRemoteRepository;

use App\GitoliteRepositoryConfiguration;
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
    public function handle()
    {
        $private_key = $this->initSshKey();
        $old_conf = new GitoliteRepositoryConfiguration(
            $this->from,
            $this->app->getUserName(),
        );
        $new_conf = new GitoliteRepositoryConfiguration(
            $this->to,
            $this->app->getUserName(),
        );

        if (! $this->remoteFileExists(
            $private_key,
            $old_conf->getConfFilePath()
        )) {
            return;
        }

        $process = $this->initSsh($private_key)
            ->execute(array_merge(
                $this->getPreCommands(),
                $this->getCommands($old_conf, $new_conf),
                $this->getPostCommands(),
            ));
        if (! $process->isSuccessful()) {
            $private_key->flushTmpFile();

            throw new ProcessFailedException($process);
        }

        $private_key = $private_key->flushTmpFile();
    }

    public function getCommands(
        GitoliteRepositoryConfiguration $old_conf,
        GitoliteRepositoryConfiguration $new_conf,
    ): array {
        $old_repository = $old_conf->getRepositoryName();
        $new_repository = $new_conf->getRepositoryName();

        // It's necessary to rename the git repo manually from the server
        // (as indicated in https://gitolite.com/gitolite/basic-admin.html#removingrenaming-a-repo)
        // So we pipe the password to a txt file (new-line necessary)
        // Sudo move it, and later remove the tmp txt pwd file
        $commands = [
            $this->getCreateTmpPwdFileCommand($old_repository),
            $this->getSudoWrappedCommand(
                'mv /home/git/repositories/'.$old_repository.'.git'.
                    ' /home/git/repositories/'.$new_repository.'.git',
                $old_repository
            ),
            $this->getRemoveTmpPwdFileCommand($old_repository),
            $this->getRmConfCommand($old_conf),
            $this->getEchoConfCommand($new_conf),
        ];

        return array_merge(
            $commands,
            $this->getAddAndCommitCommands(
                'Renamed '.$old_conf->getConfFilename().
                ' to '.$new_conf->getConfFilename()
            )
        );
    }
}
