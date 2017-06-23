<?php

namespace Kioku\FileSystem;

class FileSystem
{
    /**
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * @param string $path
     * @param bool $lock
     * @return bool|string
     * @throws FileNotFoundException
     */
    public function get(string $path, bool $lock = false)
    {
        if ($this->isFile($path)) {
            return $lock ? $this->sharedGet($path) : file_get_contents($path);
        }

        throw new FileNotFoundException("File does not exist at path {$path}");
    }

    /**
     * @param string $path
     * @return bool|string
     */
    public function sharedGet(string $path)
    {
        $contents = '';
        $handle = fopen($path, 'rb');

        if ($handle) {
            try {
                if (flock($handle, LOCK_SH)) {
                    clearstatcache(true, $path);
                    $contents = fread($handle, $this->size($path) ?: 1);
                    flock($handle, LOCK_UN);
                }
            } finally {
                fclose($handle);
            }
        }

        return $contents;
    }

    /**
     * @param string $path
     * @return mixed
     * @throws FileNotFoundException
     */
    public function getRequire(string $path)
    {
        if ($this->isFile($path)) {
            return require $path;
        }

        throw new FileNotFoundException("File does not exist at path {$path}");
    }

    /**
     * @param string $file
     */
    public function requireOnce(string $file)
    {
        require_once $file;
    }

    /**
     * @param string $path
     * @param string $contents
     * @param bool $lock
     * @return bool|int
     */
    public function put(string $path, string $contents, bool $lock = false)
    {
        return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
    }

    /**
     * @param string $path
     * @param string $data
     * @return bool|int
     */
    public function append(string $path, string $data)
    {
        return file_put_contents($path, $data, FILE_APPEND);
    }

    /**
     * @param string | array $paths
     * @return bool
     */
    public function delete($paths): bool
    {
        $paths = is_array($paths) ? $paths : func_get_args();
        $success = true;

        foreach ($paths as $path) {
            try {
                if (!@unlink($path)) {
                    $success = false;
                }
            } catch (\ErrorException $e) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param string $path
     * @param string $target
     * @return bool
     */
    public function move(string $path, string $target): bool
    {
        return rename($path, $target);
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function name(string $path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function basename(string $path)
    {
        return pathinfo($path, PATHINFO_BASENAME);
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function dirname(string $path)
    {
        return pathinfo($path, PATHINFO_DIRNAME);
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function extension(string $path)
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * @param string $path
     * @param string $target
     * @return bool
     */
    public function copy(string $path, string $target): bool
    {
        return copy($path, $target);
    }

    /**
     * @param string $path
     * @return string
     */
    public function type(string $path): string
    {
        return filetype($path);
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function mimeType(string $path)
    {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }

    /**
     * @param string $path
     * @return int
     */
    public function size(string $path): int
    {
        return filesize($path);
    }

    /**
     * @param string $path
     * @return bool|int
     */
    public function lastModified(string $path)
    {
        return filemtime($path);
    }

    /**
     * @param string $path
     * @return bool
     */
    public function isWritable(string $path): bool
    {
        return is_writable($path);
    }

    /**
     * @param string $file
     * @return bool
     */
    public function isFile(string $file): bool
    {
        return is_file($file);
    }

    /**
     * @param string $pattern
     * @param int $flags
     * @return array
     */
    public function glob(string $pattern, int $flags = 0): array
    {
        return glob($pattern, $flags);
    }
}