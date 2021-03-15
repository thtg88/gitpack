<?php

namespace App\GitoliteAdminRepository;

class Conf
{
    /** @var string */
    private const FILE_EXTENSION = 'conf';

    /** @var string */
    private const FOLDER_NAME = 'conf/repos';

    /**
     * Create a new Gitolite repository configuration instance.
     *
     * @param string $repository_name
     * @param string $user_name
     * @return void
     */
    public function __construct(
        private string $repository_name,
        private string $user_name,
    ) {
    }

    public function getRepositoryName(): string
    {
        return $this->repository_name;
    }

    /**
     * Return the conf file path from the current object state.
     *
     * @param string
     */
    public function getConfFilePath(): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            Utils::BASE_PATH,
            self::FOLDER_NAME,
            $this->getConfFilename()
        ]);
    }

    /**
     * Return the conf file name from the current object state.
     *
     * @param string
     */
    public function getConfFilename(): string
    {
        return implode('.', [
            $this->getRepositoryName(),
            self::FILE_EXTENSION,
        ]);
    }

    /**
     * Return the Gitolite repository config to include in the config file.
     *
     * @return string
     */
    public function output(): string
    {
        return 'repo '.$this->repository_name."\n".
            '    RW+     =   '.$this->user_name;
    }
}
