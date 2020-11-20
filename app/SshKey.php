<?php

namespace App;

final class SshKey
{
    /**
     * The key contents.
     *
     * @var string
     */
    protected $contents;

    /**
     * The key tmp file descriptor.
     *
     * @var resource
     */
    protected $tmp_file;

    /**
     * The key tmp file name.
     *
     * @var string
     */
    protected $tmp_filename;

    /**
     * Create new SSH key instance.
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Create an SSH key from the given contents.
     *
     * @param string $contents
     * @return self
     */
    public static function createFromContents(string $contents): self
    {
        return (new self())->setContents($contents);
    }

    public function getTmpFilename(): string
    {
        return $this->tmp_filename;
    }

    /**
     * Set the SSH key contents, and make sure it is in the right format.
     *
     * @param string $contents
     * @return self
     */
    public function setContents(string $contents): self
    {
        $this->contents = trim($contents)."\n";

        return $this;
    }

    /**
     * Save the SSH key as a temporary file for usage across the app.
     *
     * @return self
     */
    public function saveAsTmpFile(): self
    {
        $this->tmp_file = tmpfile();
        $this->tmp_filename = stream_get_meta_data($this->tmp_file)['uri'];

        fwrite($this->tmp_file, $this->contents);

        return $this;
    }

    /**
     * You MUST call this function when you finished using the file,
     * so that the tmp file gets deleted.
     *
     * @return self
     */
    public function flushTmpFile(): self
    {
        if ($this->tmp_file === null) {
            return $this;
        }

        fclose($this->tmp_file);

        $this->tmp_file = null;
        $this->tmp_filename = null;

        return $this;
    }
}
