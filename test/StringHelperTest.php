<?php

namespace test;

require_once __DIR__ . '/../index.php';
define("ROOT_PATH", dirname(__DIR__) . "/");

use core\yaecho\help\StringHelper;
use PHPUnit\Framework\TestCase;


class StringHelperTest extends TestCase
{
    public function testCamelize()
    {
        $this->assertEquals('array_help', StringHelper::uncamelize('ArrayHelp'));
    }
}
