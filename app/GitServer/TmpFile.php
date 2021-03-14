<?php

namespace App\GitServer;

/**
 * This class represents a tmp file saved on the server,
 * which MUST be "destroyed" after finishing using it.
 */
abstract class TmpFile
{
    protected ?string $contents;

    /**
     * The tmp file descriptor.
     *
     * @var resource
     */
    protected $tmp_file;

    protected ?string $tmp_filename;

    protected function __construct()
    {
    }

    /**
     * Create an SSH key from the given contents.
     *
     * @param string $contents
     * @return static
     */
    public static function createFromContents(string $contents): static
    {
        return (new static())->setContents($contents);
    }

    public function getTmpFilename(): string
    {
        return $this->tmp_filename;
    }

    /**
     * Set the SSH key contents, and make sure it is in the right format.
     *
     * @param string $contents
     * @return static
     */
    public function setContents(string $contents): static
    {
        $this->contents = trim($contents)."\n";

        return $this;
    }

    /**
     * Save the SSH key as a temporary file for usage across the app.
     *
     * @return static
     */
    public function saveAsTmpFile(): static
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
     * @return static
     */
    public function flushTmpFile(): static
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
