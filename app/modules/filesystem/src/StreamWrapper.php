<?php

namespace Pagekit\Filesystem;

class StreamWrapper
{
    /**
     * @var resource
     */
    protected $handle;

    protected static ?\Pagekit\Filesystem\Filesystem $file = null;

    /**
     * @param Filesystem $file
     */
    public static function setFilesystem(Filesystem $file): void
    {
        static::$file = $file;
    }

    /**
     * Close directory handle.
     */
    public function dir_closedir(): bool
    {
        closedir($this->handle);

        return true;
    }

    /**
     * Open directory handle.
     *
     * @param  string $path
     * @param  int    $options
     */
    public function dir_opendir($path, $options): bool
    {
        $this->handle = opendir(self::$file->getPath($path, true));

        return (bool) $this->handle;
    }

    /**
     * Read entry from directory handle.
     *
     * @return string
     */
    public function dir_readdir()
    {
        return readdir($this->handle);
    }

    /**
     * Rewind directory handle.
     */
    public function dir_rewinddir(): bool
    {
        rewinddir($this->handle);

        return true;
    }

    /**
     * Create a directory.
     *
     * @param  string $path
     * @param  int    $mode
     * @param  int    $options
     */
    public function mkdir($path, $mode, $options): bool
    {
        return mkdir(self::$file->getPath($path, true), $mode, $options & STREAM_MKDIR_RECURSIVE);
    }

    /**
     * Renames a file or directory.
     *
     * @param  string $pathFrom
     * @param  string $pathTo
     */
    public function rename($pathFrom, $pathTo): bool
    {
        return rename(self::$file->getPath($pathFrom, true), self::$file->getPath($pathTo, true));
    }

    /**
     * Removes a directory.
     *
     * @param  string $path
     * @param  int    $options
     */
    public function rmdir($path, $options): bool
    {
        return rmdir(self::$file->getPath($path, true));
    }

    /**
     * Delete a file.
     *
     * @param  $path string
     */
    public function unlink($path): bool
    {
        return unlink(self::$file->getPath($path, true));
    }

    /**
     * Retrieve information about a file.
     *
     * @param  string $path
     * @param  int    $flags
     * @return array
     */
    public function url_stat($path, $flags)
    {
        $path = self::$file->getPath($path, true);

        if ($flags & STREAM_URL_STAT_QUIET || !file_exists($path)) {
            return @stat($path);
        }

        return stat($path);
    }

    /**
     * Retrieve the underlaying resource.
     *
     * @param  int $castAs
     * @return resource
     */
    public function stream_cast($castAs): bool
    {
        return false;
    }

    /**
     * Close an resource.
     */
    public function stream_close(): void
    {
        fclose($this->handle);
    }

    /**
     * Tests for end-of-file on a file pointer.
     */
    public function stream_eof(): bool
    {
        return feof($this->handle);
    }

    /**
     * Flushes the output.
     */
    public function stream_flush(): bool
    {
        return fflush($this->handle);
    }

    /**
     * Advisory file locking.
     *
     * @param  int $operation
     */
    public function stream_lock($operation): bool
    {
        if (in_array($operation, [LOCK_SH, LOCK_EX, LOCK_UN, LOCK_NB])) {
            return flock($this->handle, $operation);
        }

        return false;
    }

    /**
     * Opens file or URL.
     *
     * @param  string $path
     * @param  string $mode
     * @param  int    $options
     * @param  string $openedPath
     */
    public function stream_open($path, $mode, $options, &$openedPath): bool
    {
        $this->handle = fopen(self::$file->getPath($path, true), $mode);

        return (bool) $this->handle;
    }

    /**
     * Read from stream.
     *
     * @param  int $count
     * @return bool
     */
    public function stream_read($count)
    {
        return fread($this->handle, $count);
    }

    /**
     * Seeks to specific location in a stream.
     *
     * @param  int $offset
     * @param  int $whence
     */
    public function stream_seek($offset, $whence): bool
    {
        return !fseek($this->handle, $offset, $whence);
    }

    /**
     * Retrieve information about a file resource.
     *
     * @return array
     */
    public function stream_stat()
    {
        return fstat($this->handle);
    }

    /**
     * Retrieve the current position of a stream.
     *
     * @return int
     */
    public function stream_tell()
    {
        return ftell($this->handle);
    }

    /**
     * Write to stream.
     *
     * @param  string $data
     * @return int
     */
    public function stream_write($data)
    {
        return fwrite($this->handle, $data);
    }
}
