<?php

namespace Kioku\Http;

final class UploadedFiles
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * UploadedFiles constructor.
     * @param array $files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->files;
    }

    /**
     * @param string $name
     * @return array|mixed
     */
    public function __get(string $name)
    {
        if (is_null($this->files[1])) {
            return $this->files[$name];
        }

        $response = [];

        foreach ($this->files as $k => $v) {
            $response[] = $v[$name];
        }

        return $response;
    }
}