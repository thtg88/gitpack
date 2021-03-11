<?php

namespace App\Http\Controllers;

use App\Jobs\StoreRemoteSshKeysJob;
use Illuminate\Http\Request;
use phpseclib3\Crypt\Common\Formats\Keys\OpenSSH;
use phpseclib3\Crypt\RSA;
use RuntimeException;
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class SshKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('ssh-keys.index.main');
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

        OpenSSH::setComment($user->email);

        $private_key = RSA::createKey();
        $public_key = $private_key->getPublicKey();

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

            $zip->addFile('id_rsa_gitpack', $private_key->toString('OpenSSH'));
            $zip->addFile(
                'id_rsa_gitpack.pub',
                $public_key->toString('OpenSSH')
            );

            $zip->finish();

            $user->update([
                'ssh_keys_last_generated_at' => now()->toDateTimeString(),
            ]);
        };

        return response()->streamDownload($callback, 'ssh-key-pair.zip');
    }
}
