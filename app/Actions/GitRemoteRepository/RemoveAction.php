<?php

namespace App\Actions\GitRemoteRepository;

use App\Actions\SshAction;
use App\GitoliteAdminRepository\Conf;
use App\Models\App;
use App\Pipelines\RemoveGitRemoteRepositoryPipeline;
use App\Travelers\GitRemoteRepository\RemoveTraveler;
use Symfony\Component\Process\Exception\ProcessFailedException;

final class RemoveAction extends SshAction
{
    public function __construct(private App $app)
    {
    }

    public function __invoke(): void
    {
        $private_key = $this->initSshKey();
        $traveler = RemoveGitRemoteRepositoryPipeline::run(
            $this->getTraveler()
        );
        $conf_path = $traveler->getGitoliteConfFilePath();

        if (! $this->remoteFileExists($private_key, $conf_path)) {
            return;
        }

        $process = $this->initSsh($private_key)
            ->execute($traveler->getCommands());
        if (! $process->isSuccessful()) {
            $private_key->flushTmpFile();

            throw new ProcessFailedException($process);
        }

        $private_key = $private_key->flushTmpFile();

        // TODO save output
        // $output = $process->getOutput();
        // dd($output);
    }

    protected function getTraveler(): RemoveTraveler
    {
        $gitolite_conf = new Conf($this->app->name, $this->app->getUserName());

        return (new RemoveTraveler())->setGitoliteConf($gitolite_conf);
    }
}
