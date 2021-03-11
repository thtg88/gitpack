<?php

namespace App\GitoliteAdminRepository;

use App\Models\User;
use phpseclib3\Crypt\RSA\PublicKey;

class Key
{
    /** @var string */
    private const FILE_EXTENSION = 'pub';

    /** @var string */
    private const FOLDER_NAME = 'keydir';

    /** @var string */
    private const PUBLIC_KEY_TYPE = 'OpenSSH';

    public function __construct(
        private User $user,
        private PublicKey $public_key,
    ) {
    }

    public function getFilename(): string
    {
        return implode('.', [
            $this->getUserName(),
            self::FILE_EXTENSION,
        ]);
    }

    public function getFilePath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            Utils::BASE_PATH,
            self::FOLDER_NAME,
            $this->getFilename(),
        ]);
    }

    public function getPublicKeyContents(): string
    {
        return $this->public_key->toString(self::PUBLIC_KEY_TYPE);
    }

    public function getUserName(): string
    {
        return $this->user->name;
    }
}
