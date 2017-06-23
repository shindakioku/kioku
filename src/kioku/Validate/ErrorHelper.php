<?php

namespace Kioku\Validate;

class ErrorHelper
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * ErrorHelper constructor.
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return !count($this->errors) ? true : false;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        if (!count($this->errors)) {
            return [];
        }

        $errors = [];

        foreach ($this->errors as $key => $value) {
            foreach ($value as $k => $v) {
                $a = [];
                $a[1] = '/:attribute/';
                $a[2] = '/:length/';

                $b = [];
                $b[1] = $key;
                $b[2] = $v['length'];

                // заменяем :name на значения
                $data = preg_replace($a, $b, $v);

                $errors[$key][] = $data['error'];
            }
        }

        return $errors;
    }
}