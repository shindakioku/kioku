<?php

namespace Kioku\Validate;

use Symfony\Component\Yaml\Yaml;

class Methods
{
    /**
     * @var Errors
     */
    protected $errors;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var Yaml
     */
    protected $yaml;

    /**
     * Methods constructor.
     */
    public function __construct()
    {
        $this->yaml = new Yaml();
        $this->errors = Errors::instance();
        $this->lang = __DIR__.'/../../config/rules/'.$this->yaml->parse(
                file_get_contents(__DIR__.'/../../config/main.yaml')
            )['validation-lang'].'.yaml';
    }

    /**
     * @param $data
     * @param string $key
     */
    public function required($data, string $key): void
    {
        if (0 == strlen($data)) {
            $this->errors->set(
                $key, [
                    'error' => $this->yaml->parse(file_get_contents($this->lang))['required'],
                ]
            );
        }
    }

    /**
     * @param $data
     * @param int $min
     * @param string $key
     */
    public function min($data, int $min, string $key): void
    {
        if ($min > strlen($data)) {
            $this->errors->set(
                $key, [
                    'length' => $min,
                    'error' => $this->yaml->parse(file_get_contents($this->lang))['min'],
                ]
            );
        }
    }

    /**
     * @param $data
     * @param int $max
     * @param string $key
     */
    public function max($data, int $max, string $key): void
    {
        if ($max < strlen($data)) {
            $this->errors->set(
                $key, [
                    'length' => $max,
                    'error' => $this->yaml->parse(file_get_contents($this->lang))['max'],
                ]
            );
        }
    }
}