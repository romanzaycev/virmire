<?php

use Virmire\Application;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    
    public function testContainerArgumentApplication()
    {
        $this->expectException('TypeError');
        $application = new Application('');
    }
    
}
