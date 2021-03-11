<?php

namespace App\Http\Controllers;

use App\Jobs\StoreRemoteSshKeysJob;
use App\SshKeyPair;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class SshKeyPairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('ssh-key-pairs.index.main');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();

        try {
            $ssh_key_pair = (new SshKeyPair($user))->writeToDisk();
        } catch (ProcessFailedException $e) {
            throw ValidationException::withMessages([
                'user_id' => ['We could not generate your keys at this time.'],
            ]);
        }

        $private_key = $ssh_key_pair->getPrivateKeyContents();
        $public_key = $ssh_key_pair->getPublicKeyContents();

        // We won't be able to use the object anymore after this point
        $ssh_key_pair = $ssh_key_pair->delete();

        // upload keys to git server
        dispatch(new StoreRemoteSshKeysJob($user, $public_key));

        $callback = static function () use ($private_key, $public_key, $user) {
            // Define suitable options for ZipStream Archive.
            $options = new Archive();
            $options->setContentType('application/octet-stream');
            $options->setSendHttpHeaders(true);
            // this is needed to prevent issues with truncated zip files
            $options->setZeroHeader(true);

            // initialise zipstream with output zip filename and options.
            $zip = new ZipStream('gitpack-ssh-key-pair.zip', $options);

            $zip->addFile('id_rsa_gitpack', $private_key);
            $zip->addFile('id_rsa_gitpack.pub', $public_key);

            $zip->finish();

            $user->update([
                'ssh_keys_last_generated_at' => now()->toDateTimeString(),
            ]);
        };

        return response()->streamDownload($callback, 'ssh-key-pair.zip');
    }
}
