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

class GitInitRemoteRepositoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        $process = Ssh::create(config('app.git_ssh.user'), config('app.git_ssh.host'))
            ->usePrivateKey($private_key->getTmpFilename())
            ->disableStrictHostKeyChecking()
            ->execute([
                'echo "'.$gitolite_conf->output().'" > '.
                    $gitolite_conf->getConfFilePath(),
                'cd '.GitoliteRepositoryConfiguration::GITOLITE_ADMIN_PATH,
                'git pull origin master',
                'git add .',
                'git commit -m "Added '.$gitolite_conf->getConfFilename().'"',
                'git push origin master',
            ]);
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
}
