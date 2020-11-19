<?php

namespace App\Jobs;

use App\Models\App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Ssh\Ssh;

class GitInitRemoteRepositoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \App\Models\App */
    protected $app;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $private_key = config('app.git_ssh.private_key');
        $public_key = config('app.git_ssh.public_key');

        $private_key_file = tmpfile();
        $private_key_file_name = stream_get_meta_data($private_key_file)['uri'];
        $public_key_file = tmpfile();
        $public_key_file_name = stream_get_meta_data($public_key_file)['uri'];
        fwrite($private_key_file, $private_key);
        fwrite($public_key_file, $public_key);

        $process = Ssh::create('marco', '176.58.114.83')
            ->disableStrictHostKeyChecking()
            ->usePrivateKey($private_key_file_name)
            ->execute('ls -la');

        $output = $process->getOutput();
        $successful = $process->isSuccessful();

        fclose($private_key_file);
        fclose($public_key_file);

        dd($output, $successful);
    }
}
