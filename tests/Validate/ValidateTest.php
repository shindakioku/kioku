<?php

namespace Kioku\Tests\Validate;

use Kioku\Validate\ErrorHelper;
use Kioku\Validate\Validate;
use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    /**
     * @var Validate
     */
    protected $validate;

    public function setUp()
    {
        $this->validate = new Validate();
    }

    public function testValidateMethod()
    {
        $validate = $this->validate->validate(
            ['login' => 'shinda', 'password' => 123123123123123], [
                'login' => 'required',
                'password' => 'min:5|max:10'
            ]
        );

        $this->assertInstanceOf(ErrorHelper::class, $validate);

        $this->assertCount(1, $validate->getErrors());

        $this->assertFalse($validate->isValid());
    }
}