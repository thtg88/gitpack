<?php

namespace App\GitoliteAdminRepository;

use App\Models\User;

class Key
{
    /** @var string */
    private const FILE_EXTENSION = 'pub';

    /** @var string */
    private const FOLDER_NAME = 'keydir';

    public function __construct(
        private User $user,
        private string $public_key,
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
        return $this->public_key;
    }

    public function getUserName(): string
    {
        return $this->user->email;
    }
}
