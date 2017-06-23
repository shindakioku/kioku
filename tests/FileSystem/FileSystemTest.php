<?php

namespace Kioku\Tests\FileSystem;

use Kioku\FileSystem\FileNotFoundException;
use Kioku\FileSystem\FileSystem;
use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase
{
    /**
     * @var FileSystem
     */
    protected $file;

    public function setUp()
    {
        $this->file = new FileSystem();
    }

    public function getBootstrap()
    {
        return [
            [__DIR__.'/../bootstrap.php'],
        ];
    }

    public function getForPutAndAppend()
    {
        return [
            [__DIR__.'/ForTest/file.php'],
        ];
    }

    /**
     * @dataProvider getBootstrap
     * @param $expected
     */
    public function testFileExists($expected)
    {
        $this->assertFalse(
            $this->file->exists('index.php')
        );

        $this->assertTrue(
            $this->file->exists($expected)
        );
    }

    /**
     * @dataProvider getBootstrap
     * @param $expected
     */
    public function testIfFile($expected)
    {
        $this->assertFalse(
            $this->file->isFile(__DIR__.'/../Container')
        );

        $this->assertTrue(
            $this->file->isFile($expected)
        );
    }

    /**
     * @dataProvider getBootstrap
     * @param $excepted
     */
    public function testGetDataFromFile($excepted)
    {
        $this->assertStringStartsWith(
            '<?php', $this->file->get($excepted)
        );

        $this->expectException(FileNotFoundException::class);
        $this->file->get('haha.php');
    }

    /**
     * @dataProvider getForPutAndAppend
     * @param $excepted
     */
    public function testPutAppendDataInFile($excepted)
    {
        $this->assertSame(
            5, $this->file->put($excepted, 'hello')
        );
        $this->assertNotSame(
            7, $this->file->put($excepted, 'hello')
        );

        $this->assertSame(
            6, $this->file->append($excepted, ' world')
        );
    }

    public function testCopy()
    {
        $this->assertTrue(
            $this->file->copy(__DIR__.'/ForTest/edit.php', __DIR__.'/ForTest/edit1.php')
        );

        $this->assertStringStartsWith(
            "It's", $this->file->get(__DIR__.'/ForTest/edit1.php')
        );
    }

    /**
     * @dataProvider getBootstrap
     * @param $excepted
     */
    public function testType($excepted)
    {
        $this->assertSame(
            'file', $this->file->type($excepted)
        );
    }

    /**
     * @dataProvider getBootstrap
     * @param $excepted
     */
    public function testSize($excepted)
    {
        $this->assertSame(
            51, $this->file->size($excepted)
        );
    }

    /**
     * @dataProvider getBootstrap
     * @param $excepted
     */
    public function testIsWritable($excepted)
    {
        $this->assertTrue(
            $this->file->isWritable($excepted)
        );
    }
}