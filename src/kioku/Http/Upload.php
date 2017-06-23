<?php

namespace Kioku\Http;

use Kioku\Helpers\Arr;

final class Upload
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * Upload constructor.
     */
    public function __construct()
    {
        $this->files = $_FILES;
    }

    /**
     * @param string $key
     * @return array|bool|UploadedFiles
     */
    public function get(string $key)
    {
        if (!Arr::has($key, $this->files)) {
            return false;
        }

        if (is_array($this->files[$key]['name'])) {
            return $this->sortManyFiles($key);
        }

        return new UploadedFiles($this->files[$key]);
    }

    /**
     * @param string $key
     * @return UploadedFiles
     */
    private function sortManyFiles(string $key): UploadedFiles
    {
        $result = [];
        $files = $this->files[$key];
        $countFiles = count($files['name']);
        $fileKeys = array_keys($files);

        for ($i = 0; $i < $countFiles; $i++) {
            foreach ($fileKeys as $k) {
                $result[$i][$k] = $files[$k][$i];
            }
        }


        return new UploadedFiles($result);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->files;
    }
}