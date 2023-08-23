<?php

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testAddition()
    {
        $result = 2 + 2;
        $this->assertEquals(4, $result);
    }
}
