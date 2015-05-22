<?php

use \Vube\Monolog\Formatter\SplunkLineFormatter;
use \Monolog\Logger;
use \Monolog\Handler\TestHandler;


class SplunkLineFormatterTest extends \PHPUnit_Framework_TestCase
{
    private $log;
    private $handler;
    private $slf;

    protected function setUp()
    {
        $this->slf = new SplunkLineFormatter();

        $this->handler = new TestHandler(Logger::DEBUG);
        $this->handler->setFormatter($this->slf);

        $this->log = new Logger('test');
        $this->log->pushHandler($this->handler);
    }

    public function testConvertToString_String()
    {
        $raw = 'asdf';
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame($raw, $x);
    }

    public function testConvertToString_Int()
    {
        $raw = 123;
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('123', $x);
    }

    public function testConvertToString_Float()
    {
        $raw = 1.2;
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('1.2', $x);
    }

    public function testConvertToString_NULL()
    {
        $raw = null;
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('NULL', $x);
    }

    public function testConvertToString_TRUE()
    {
        $raw = true;
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('true', $x);
    }

    public function testConvertToString_FALSE()
    {
        $raw = false;
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('false', $x);
    }

    public function testConvertToString_Zero()
    {
        $raw = 0;
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('0', $x);
    }

    public function testConvertToString_AssocSimpleScalar()
    {
        $raw = array(
            'a' => 'A',
        );
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('a=A', $x);
    }

    public function testConvertToString_AssocExpandedScalar()
    {
        $raw = array(
            'a' => 'A A',
        );
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('a="A A"', $x);
    }

    public function testConvertToString_AssocNULL()
    {
        $raw = array(
            'a' => null,
        );
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('a=NULL', $x);
    }

    public function testConvertToString_AssocBool()
    {
        $raw = array(
            'a' => true,
            'b' => false,
        );
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('a=true b=false', $x);
    }

    public function testConvertToString_AssocNumeric()
    {
        $raw = array(
            'a' => 1,
            'b' => 2.3,
            'c' => -4,
            'd' => -5.6,
        );
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('a=1 b=2.3 c=-4 d=-5.6', $x);
    }

    public function testConvertToString_AssocLists()
    {
        $raw = array(
            'a' => array(1),
        );
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('a="[1]"', $x);
    }

    public function testConvertToString_AssocAssoc()
    {
        $raw = array(
            'a' => array('b' => 1),
        );
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('a="{^b^:1}"', $x);
    }

    public function testConvertToString_AssocAssoc_CustomQuotes()
    {
        $raw = array(
            'a' => array('b' => 1),
        );
        $this->slf->setQuoteReplacement('@');
        $x = $this->slf->publicConvertToString($raw);
        $this->assertSame('a="{@b@:1}"', $x);
    }

    public function testLogFormat()
    {
        $context = array(
            'a' => 'A A',
        );
        $this->log->addNotice('message here', $context);
        $records = $this->handler->getRecords();
        $this->assertSame(1, count($records));
        $message = $records[0]['formatted'];
        $this->assertTrue((bool) preg_match('/^\d+ test\.NOTICE L=250 message here a="A A"\s*\n$/', $message),
            "Message doesn't match expected regex: $message");
    }

    public function testLogFormatWithQuotesInMessage()
    {
        $context = array(
            'a' => 'A A',
        );
        $this->log->addWarning('message "with quotes"', $context);
        $records = $this->handler->getRecords();
        $this->assertSame(1, count($records));
        $message = $records[0]['formatted'];
        $this->assertTrue((bool) preg_match('/^\d+ test\.WARNING L=300 message "with quotes" a="A A"\s*\n$/', $message),
            "Message doesn't match expected regex: $message");
    }

    public function testLogWithAssocArrayInContext()
    {
        $context = array(
            'cdata' => array(
                'ca' => 'CA',
            ),
        );
        $this->log->addWarning('message', $context);
        $records = $this->handler->getRecords();
        $this->assertSame(1, count($records));
        $message = $records[0]['formatted'];
        $this->assertTrue((bool) preg_match('/^\d+ test\.WARNING L=300 message cdata="\{\^ca\^:\^CA\^\}"\s*\n$/', $message),
            "Message doesn't match expected regex: $message");
    }
}
