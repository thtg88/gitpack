<?php

namespace App\Jobs\GitRemoteRepository;

use App\GitoliteRepositoryConfiguration;
use App\SshKey;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;

abstract class Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected function executeCommands(
        SshKey $private_key,
        GitoliteRepositoryConfiguration $gitolite_conf,
    ): Process {
        return $this->initSsh($private_key)
            ->execute($this->getAllCommands($gitolite_conf));
    }

    protected function getAllCommands(
        GitoliteRepositoryConfiguration $conf
    ): array {
        return array_merge(
            $this->getPreCommands(),
            $this->getCommands($conf),
            $this->getPostCommands(),
        );
    }

    protected function getPreCommands(): array
    {
        return [
            'cd '.GitoliteRepositoryConfiguration::GITOLITE_ADMIN_PATH,
            'git pull origin master',
        ];
    }

    protected function getPostCommands(): array
    {
        return ['git push origin master'];
    }

    protected function getCreateTmpPwdFileCommand(string $repository): string
    {
        return "echo \"".config('app.git_ssh.sudo_password')."\n\"".
                " > ".$this->getPwdTmpFilePath($repository);
    }

    protected function getRemoveTmpPwdFileCommand(string $repository): string
    {
        return 'rm '.$this->getPwdTmpFilePath($repository);
    }

    protected function getSudoWrappedCommand(string $command, string $repository): string
    {
        return 'sudo -S '.$command.' <'.$this->getPwdTmpFilePath($repository);
    }

    protected function getPwdTmpFilePath(string $repository): string
    {
        return '~/pwd-'.$repository.'.tmp';
    }

    protected function getRmConfCommand(
        GitoliteRepositoryConfiguration $conf
    ): string {
        return 'rm '.$conf->getConfFilePath();
    }

    protected function getEchoConfCommand(
        GitoliteRepositoryConfiguration $conf
    ): string {
        return 'echo "'.$conf->output().'" > '.$conf->getConfFilePath();
    }

    protected function getAddAndCommitCommands(string $commit_message): array
    {
        return [
            'git add .',
            'git commit -m "'.$commit_message.'"',
        ];
    }

    protected function initSshKey(): SshKey
    {
        return SshKey::createFromContents(config('app.git_ssh.private_key'))
            ->saveAsTmpFile();
    }

    protected function initSsh(SshKey $private_key): Ssh
    {
        return Ssh::create(
            config('app.git_ssh.user'),
            config('app.git_ssh.host'),
        )
            ->usePrivateKey($private_key->getTmpFilename())
            ->disableStrictHostKeyChecking();
    }

    protected function remoteFileExists(
        SshKey $private_key,
        string $path
    ): bool {
        return $this->initSsh($private_key)
            ->execute('[ -f '.$path.' ]')
            ->isSuccessful();
    }
}
