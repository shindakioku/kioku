<?php

namespace Kioku\Validate;

use Kioku\Helpers\Arr;

class Validate
{
    /**
     * @var Methods
     */
    private $methods;

    /**
     * Validate constructor.
     */
    public function __construct()
    {
        $this->methods = new Methods();
    }

    /**
     * @param array $data
     * @param array $rules
     * @return ErrorHelper
     */
    public function validate(array $data, array $rules): ErrorHelper
    {
        foreach ($rules as $key => $rule) {
            if (stristr($key, '.')) {
                $nameInput = explode('.', $key);
                $valueFromInput = Arr::get($nameInput, $data);

                $this->toValidate(implode(' ', $nameInput), $valueFromInput, $rule);
            } else {
                $valueFromInput = $data[$key];

                $this->toValidate($key, $valueFromInput, $rule);
            }
        }

        $errors = Errors::instance();

        return new ErrorHelper(
            count($errors->errors) ? $errors->errors : []
        );
    }

    /**
     * @param string $key
     * @param $inputValue
     * @param string $rules
     */
    private function toValidate(string $key, $inputValue, string $rules): void
    {
        if ($arrayRules = explode('|', $rules)) {
            foreach ($arrayRules as $k) {
                $this->getValidate($key, $inputValue, $k);
            }
        } else {
            $this->getValidate($key, $inputValue, $rules);
        }
    }

    /**
     * @param string $key
     * @param $inputValue
     * @param string $rules
     */
    private function getValidate(string $key, $inputValue, string $rules): void
    {
        if (stristr($rules, ':')) {
            $rule = explode(':', $rules);

            call_user_func_array(
                [$this->methods, $rule[0]], [$inputValue, $rule[1], $key]
            );
        } else {
            call_user_func_array(
                [$this->methods, $rules], [$inputValue, $key]
            );
        }
    }
}