<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Invoiced\Client;
use Invoiced\File;

class FileTest extends PHPUnit_Framework_TestCase
{
    public static $invoiced;

    public static function setUpBeforeClass()
    {
        $mock = new MockHandler([
            new Response(201, [], '{"id":456,"name":"Filename"}'),
            new Response(200, [], '{"id":456,"name":"Filename"}'),
            new Response(204),
        ]);

        self::$invoiced = new Client('API_KEY', false, false, $mock);
    }

    public function testGetEndpoint()
    {
        $file = new File(self::$invoiced, 123);
        $this->assertEquals('/files/123', $file->getEndpoint());
    }

    public function testCreate()
    {
        $file = new File(self::$invoiced, null, []);
        $file = $file->create(['name' => 'Filename']);

        $this->assertInstanceOf('Invoiced\\File', $file);
        $this->assertEquals(456, $file->id);
        $this->assertEquals('Filename', $file->name);
    }

    public function testRetrieveNoId()
    {
        $this->setExpectedException('InvalidArgumentException');
        $file = new File(self::$invoiced, null, []);
        $file->retrieve(false);
    }

    public function testRetrieve()
    {
        $file = new File(self::$invoiced, null, []);
        $file = $file->retrieve(456);

        $this->assertInstanceOf('Invoiced\\File', $file);
        $this->assertEquals(456, $file->id);
        $this->assertEquals('Filename', $file->name);
    }

    public function testDelete()
    {
        $file = new File(self::$invoiced, 456, []);
        $this->assertTrue($file->delete());
    }
}
