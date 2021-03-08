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

        if (! $this->remoteFileExists(
            $private_key,
            $gitolite_conf->getConfFilePath()
        )) {
            return;
        }

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

    private function remoteFileExists(SshKey $private_key, string $path): bool
    {
        return $this->initSsh($private_key)
            ->execute('[ -f '.$path.' ]')
            ->isSuccessful();
    }

    private function getCommands(GitoliteRepositoryConfiguration $conf): array
    {
        $repository_name = $conf->getRepositoryName();

        return [
            'rm '.$conf->getConfFilePath(),
            // It's necessary to remove the git repo manually from the server
            // (as indicated in https://gitolite.com/gitolite/basic-admin.html#removingrenaming-a-repo)
            // So we pipe the password to a txt file (new-line necessary)
            // Sudo remove it, and later remove the tmp txt pwd file
            "echo \"".config('app.git_ssh.sudo_password')."\n\"".
                " > ~/pwd-".$repository_name.".txt",
            'sudo -S rm -rf /home/git/repositories/'.$repository_name.'.git'.
                ' <~/pwd-'.$repository_name.'.txt',
            'rm ~/pwd-'.$repository_name.'.txt',
            'cd '.GitoliteRepositoryConfiguration::GITOLITE_ADMIN_PATH,
            'git pull origin master',
            'git add .',
            'git commit -m "Removed '.$conf->getConfFilename().'"',
            'git push origin master',
        ];
    }
}
