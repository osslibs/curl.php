<?php

namespace osslibs\Curl;

use PHPUnit\Framework\TestCase;

class CurlTest extends TestCase
{
    public function testHeaders()
    {
        $headers = ['aA: bB', 'cC: dD'];
        $expect = ['aA' => 'bB', 'cC' => 'dD'];

        $curl = new CurlHandler();
        $curl->setopt(CURLOPT_CUSTOMREQUEST, "GET");
        $curl->setopt(CURLOPT_URL, "http://headers.jsontest.com/");
        $curl->setopt(CURLOPT_HTTPHEADER, $headers);
        $curl->setopt(CURLOPT_RETURNTRANSFER, 1);

        $this->assertArraySubset($expect, (array)json_decode($curl->exec()));
        $this->assertNotSame(0, $curl->getinfo(CURLINFO_RESPONSE_CODE));
        $this->assertSame(0, $curl->errno());
        $this->assertSame('', $curl->error());
    }

    public function testBody()
    {
        $text = json_encode((object)['a' => 'A', 'b' => 'B']);
        $expect = ['original' => $text, 'md5' => md5($text)];

        $curl = new CurlHandler();
        $curl->setopt(CURLOPT_CUSTOMREQUEST, "POST");
        $curl->setopt(CURLOPT_URL, "http://md5.jsontest.com/");
        $curl->setopt(CURLOPT_RETURNTRANSFER, 1);
        $curl->setopt(CURLOPT_POSTFIELDS, 'text=' . $text);

        $this->assertArraySubset($expect, (array)json_decode($curl->exec()));
        $this->assertNotSame(0, $curl->getinfo(CURLINFO_RESPONSE_CODE));
        $this->assertSame(0, $curl->errno());
        $this->assertSame('', $curl->error());
    }

    public function testBadProtocol()
    {
        $text = json_encode((object)['a' => 'A', 'b' => 'B']);
        $expect = ['original' => $text, 'md5' => md5($text)];

        $curl = new CurlHandler();
        $curl->setopt(CURLOPT_CUSTOMREQUEST, "POST");
        $curl->setopt(CURLOPT_URL, "badscheme://blahblah");
        $curl->setopt(CURLOPT_RETURNTRANSFER, 1);

        $data = $curl->exec();

        $this->assertSame(false, $curl->exec());
        $this->assertSame(0, $curl->getinfo(CURLINFO_RESPONSE_CODE));
        $this->assertNotSame(0, $curl->errno());
        $this->assertNotSame('', $curl->error());
    }

    public function testFailedToConnect()
    {
        $text = json_encode((object)['a' => 'A', 'b' => 'B']);
        $expect = ['original' => $text, 'md5' => md5($text)];

        $curl = new CurlHandler();
        $curl->setopt(CURLOPT_CUSTOMREQUEST, "POST");
        $curl->setopt(CURLOPT_URL, "badscheme://blahblah");
        $curl->setopt(CURLOPT_RETURNTRANSFER, 1);

        $data = $curl->exec();

        $this->assertSame(false, $curl->exec());
        $this->assertSame(0, $curl->getinfo(CURLINFO_RESPONSE_CODE));
        $this->assertNotSame(0, $curl->errno());
        $this->assertNotSame('', $curl->error());
    }

    public function testTimeout()
    {
        $text = json_encode((object)['a' => 'A', 'b' => 'B']);
        $expect = ['original' => $text, 'md5' => md5($text)];
        $timeout = 1;

        $curl = new CurlHandler();
        $curl->setopt(CURLOPT_CUSTOMREQUEST, "POST");
        $curl->setopt(CURLOPT_URL, "http://headers.jsontest.com/");
        $curl->setopt(CURLOPT_RETURNTRANSFER, 1);
        $curl->setopt(CURLOPT_CONNECTTIMEOUT_MS, $timeout);

        $this->assertSame(false, $curl->exec());
        $this->assertSame(0, $curl->getinfo(CURLINFO_RESPONSE_CODE));
        $this->assertNotSame(0, $curl->errno());
        $this->assertNotSame('', $curl->error());
    }
}
