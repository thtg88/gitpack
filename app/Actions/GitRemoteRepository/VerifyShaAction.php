<?php

namespace App\Actions\GitRemoteRepository;

use App\Actions\SshAction;
use App\GitoliteAdminRepository\Conf;
use App\Models\App;
use App\Pipelines\VerifyGitRemoteRepositoryShaPipeline;
use App\Travelers\GitRemoteRepository\VerifyShaTraveler;
use Symfony\Component\Process\Exception\ProcessFailedException;

final class VerifyShaAction extends SshAction
{
    /** @var string */
    private const COMMIT_SHA_TYPE = 'commit';

    public function __construct(
        private App $app,
        private string $sha,
        private string $timestamp,
    ) {
    }

    public function __invoke(): bool
    {
        $private_key = $this->initSshKey();
        $commands = VerifyGitRemoteRepositoryShaPipeline::run(
            $this->getTraveler()
        )->getCommands();

        $process = $this->initSsh($private_key)->execute($commands);
        // Failed command
        // Scenario 1: SHA not valid
        // sudo git --git-dir=/home/git/repositories/redux.git rev-parse \
        //     --verify abcdef
        // Output
        // fatal: Needed a single revision
        // Scenario 2: SHA N/A
        // sudo git --git-dir=/home/git/repositories/redux.git cat-file \
        //     -t 1234567890123456789012345678901234567890
        // fatal: git cat-file: could not get object info
        if (! $process->isSuccessful()) {
            $private_key->flushTmpFile();

            // throw new ProcessFailedException($process);

            return false;
        }

        $private_key->flushTmpFile();

        // TODO save output

        [$verified_sha, $type] = explode(PHP_EOL, trim($process->getOutput()));

        // Successful command should return the passed full Git SHA
        // So we prevent passing a partial one
        // sudo git --git-dir=/home/git/repositories/redux.git rev-parse \
        //     --verify 5204eeb358f7fb553241c593facb7
        // 5204eeb358f7fb553241c593facb7b7090dcdba5
        // commit
        if ($this->sha !== $verified_sha) {
            return false;
        }

        // Successful command should return only commits
        // <type> can be one of: blob, tree, commit, tag
        // sudo git --git-dir=/home/git/repositories/redux.git rev-parse \
        //     --verify 5204eeb358f7fb553241c593facb7b7090dcdba6
        // sudo git --git-dir=/home/git/repositories/redux.git cat-file \
        //     -t 5204eeb358f7fb553241c593facb7b7090dcdba5
        // Output:
        // 5204eeb358f7fb553241c593facb7b7090dcdba6
        // tag
        if ($type !== self::COMMIT_SHA_TYPE) {
            return false;
        }

        // Successful command:
        // sudo git --git-dir=/home/git/repositories/redux.git rev-parse \
        //     --verify 5204eeb358f7fb553241c593facb7b7090dcdba6
        // sudo git --git-dir=/home/git/repositories/redux.git cat-file \
        //     -t 5204eeb358f7fb553241c593facb7b7090dcdba5
        // Output:
        // 5204eeb358f7fb553241c593facb7b7090dcdba6
        // commit
        return true;
    }

    protected function getTraveler(): VerifyShaTraveler
    {
        // We need to be able to read all repos
        // the user in the config allows us to do that
        $conf = new Conf($this->app->name, config('app.git_ssh.user'));

        return (new VerifyShaTraveler())->setGitoliteConf($conf)
            ->setSha($this->sha)
            ->setTimestamp($this->timestamp);
    }
}
