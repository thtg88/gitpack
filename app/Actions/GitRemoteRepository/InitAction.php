<?php

namespace App\Actions\GitRemoteRepository;

use App\Actions\SshAction;
use App\GitoliteAdminRepository\Conf;
use App\Models\App;
use App\Pipelines\InitGitRemoteRepositoryPipeline;
use App\Travelers\GitRemoteRepository\InitTraveler;
use Symfony\Component\Process\Exception\ProcessFailedException;

final class InitAction extends SshAction
{
    public function __construct(private App $app)
    {
    }

    public function __invoke(): void
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
