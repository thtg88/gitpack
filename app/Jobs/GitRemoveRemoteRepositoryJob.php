<?php

namespace App\Jobs;

use App\GitoliteRepositoryConfiguration;
use App\Models\App;
use App\SshKey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GitRemoveRemoteRepositoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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


        $process = $this->initSsh($private_key)
            ->execute($this->getCommands($gitolite_conf));
        if (! $process->isSuccessful()) {
            $private_key->flushTmpFile();

            throw new ProcessFailedException($process);
        }

        $private_key = $private_key->flushTmpFile();

        // TODO save output
        // $output = $process->getOutput();
        // dd($output);
    }

    private function initSshKey(): SshKey
    {
        return SshKey::createFromContents(config('app.git_ssh.private_key'))
            ->saveAsTmpFile();
    }

    private function initSsh(SshKey $private_key): Ssh
    {
        return Ssh::create(
            config('app.git_ssh.user'),
            config('app.git_ssh.host'),
        )
            ->usePrivateKey($private_key->getTmpFilename())
            ->disableStrictHostKeyChecking();
    }

    private function getCommands(GitoliteRepositoryConfiguration $conf): array
    {
        return [
            'rm '.$conf->getConfFilePath(),
            'cd '.GitoliteRepositoryConfiguration::GITOLITE_ADMIN_PATH,
            'git pull origin master',
            'git add .',
            'git commit -m "Removed '.$conf->getConfFilename().'"',
            'git push origin master',
        ];
    }
}
