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

        $data = $curl->exec();

        $this->assertLessThan(300, $curl->getinfo(CURLINFO_RESPONSE_CODE));
        $this->assertArraySubset($expect, (array)json_decode($data));
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

        $data = $curl->exec();

        $this->assertLessThan(300, $curl->getinfo(CURLINFO_RESPONSE_CODE));
        $this->assertArraySubset($expect, (array)json_decode($data));
    }

    public function testCannotConnect()
    {
        $text = json_encode((object)['a' => 'A', 'b' => 'B']);
        $expect = ['original' => $text, 'md5' => md5($text)];

        $curl = new CurlHandler();
        $curl->setopt(CURLOPT_CUSTOMREQUEST, "POST");
        $curl->setopt(CURLOPT_URL, "badscheme://badsub.badhost.badtld/badpath");
        $curl->setopt(CURLOPT_RETURNTRANSFER, 1);

        $data = $curl->exec();

        $this->assertSame(false, $data);
        $this->assertSame(0, $curl->getinfo(CURLINFO_RESPONSE_CODE));
    }

    public function testTimeout()
    {
        $text = json_encode((object)['a' => 'A', 'b' => 'B']);
        $expect = ['original' => $text, 'md5' => md5($text)];

        $curl = new CurlHandler();
        $curl->setopt(CURLOPT_CUSTOMREQUEST, "POST");
        $curl->setopt(CURLOPT_URL, "badscheme://badsub.badhost.badtld/badpath");
        $curl->setopt(CURLOPT_RETURNTRANSFER, 1);
        $curl->setopt(CURLOPT_TIMEOUT_MS, 5);

        $data = $curl->exec();

        $this->assertSame(false, $data);
        $this->assertSame(0, $curl->getinfo(CURLINFO_RESPONSE_CODE));
    }
}
