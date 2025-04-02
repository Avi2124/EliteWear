<?php

namespace Illuminate\Http;

use Illuminate\Container\Container;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class UploadedFile extends SymfonyUploadedFile
{
    use FileHelpers, Macroable;

    /**
     * Begin creating a new file fake.
     *
     * @return \Illuminate\Http\Testing\FileFactory
     */
    public static function fake()
    {
        return new FileFactory;
    }

    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  string  $path
     * @param  array|string  $options
     * @return string|false
     */
    public function store($path, $options = [])
    {
        return $this->storeAs($path, $this->hashName(), $this->parseOptions($options));
    }

    /**
     * Store the uploaded file on a filesystem disk with public visibility.
     *
     * @param  string  $path
     * @param  array|string  $options
     * @return string|false
     */
    public function storePublicly($path, $options = [])
    {
        // Parse any additional options provided for the storage process
        $options = $this->parseOptions($options);

        // Set the visibility to 'public' so the file can be accessed publicly via URL
        $options['visibility'] = 'public';

        // Store the file and return its path
        return $this->storeAs($path, $this->hashName(), $options);
    }

    /**
     * Store the uploaded file on a filesystem disk with public visibility.
     *
     * @param  string  $path
     * @param  string  $name
     * @param  array|string  $options
     * @return string|false
     */
    public function storePubliclyAs($path, $name, $options = [])
    {
        // Parse options to handle storage on specific disk
        $options = $this->parseOptions($options);

        // Ensure the file has public visibility
        $options['visibility'] = 'public';

        // Store the file under a specific name with the provided options
        return $this->storeAs($path, $name, $options);
    }

    /**
     * Store the uploaded file on a filesystem disk.
     *
     * @param  string  $path
     * @param  string  $name
     * @param  array|string  $options
     * @return string|false
     */
    public function storeAs($path, $name, $options = [])
    {
        // Parse any options like specifying a particular disk
        $options = $this->parseOptions($options);

        // Get the disk where the file will be stored (e.g., 'public' disk)
        $disk = Arr::pull($options, 'disk');

        // Store the file on the specified disk and return its path
        return Container::getInstance()->make(FilesystemFactory::class)->disk($disk)->putFileAs(
            $path, $this, $name, $options
        );
    }

    /**
     * Get the contents of the uploaded file.
     *
     * @return bool|string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function get()
    {
        if (! $this->isValid()) {
            throw new FileNotFoundException("File does not exist at path {$this->getPathname()}.");
        }

        return file_get_contents($this->getPathname());
    }

    /**
     * Get the file's extension supplied by the client.
     *
     * @return string
     */
    public function clientExtension()
    {
        return $this->guessClientExtension();
    }

    /**
     * Create a new file instance from a base instance.
     *
     * @param  \Symfony\Component\HttpFoundation\File\UploadedFile  $file
     * @param  bool  $test
     * @return static
     */
    public static function createFromBase(SymfonyUploadedFile $file, $test = false)
    {
        return $file instanceof static ? $file : new static(
            $file->getPathname(),
            $file->getClientOriginalName(),
            $file->getClientMimeType(),
            $file->getError(),
            $test
        );
    }

    /**
     * Parse and format the given options.
     *
     * @param  array|string  $options
     * @return array
     */
    protected function parseOptions($options)
    {
        // If the options are a string (disk), convert to an array
        if (is_string($options)) {
            $options = ['disk' => $options];
        }

        return $options;
    }
}
