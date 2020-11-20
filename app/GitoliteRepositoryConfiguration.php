<?php

namespace App;

class GitoliteRepositoryConfiguration
{
    const GITOLITE_ADMIN_PATH = '~/gitolite-admin';
    const GITOLITE_ADMIN_CONF_FOLDER_NAME = 'conf';

    /** @var string */
    protected $repository_name;

    /** @var string */
    protected $user_name;

    /**
     * Create a new Gitolite repository configuration instance.
     *
     * @param string $repository_name
     * @param string $user_name
     * @return void
     */
    public function __construct(string $repository_name, string $user_name)
    {
        $this->repository_name = $repository_name;
        $this->user_name = $user_name;
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
