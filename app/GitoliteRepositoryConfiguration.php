<?php

namespace App;

class GitoliteRepositoryConfiguration
{
    public const GITOLITE_ADMIN_PATH = '~/gitolite-admin';
    public const GITOLITE_ADMIN_CONF_FOLDER_NAME = 'conf';

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
        return self::GITOLITE_ADMIN_PATH.DIRECTORY_SEPARATOR.
            self::GITOLITE_ADMIN_CONF_FOLDER_NAME.DIRECTORY_SEPARATOR.
            $this->getConfFilename();
    }

    /**
     * Return the conf file name from the current object state.
     *
     * @param string
     */
    public function getConfFilename(): string
    {
        return $this->getRepositoryName().'.conf';
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
