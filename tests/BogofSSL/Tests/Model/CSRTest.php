<?php

namespace BogofSSL\Tests\Model;

use BogofSSL\Model\CSR;

class CSRTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->csr = new CSR();
    }

    public function testExtractDigest()
    {
        include(__DIR__.'/fixtures/csr_info.array');
        $digest = $this->csr->extractDigest($csrInfo);
        $this->assertSame('mdc2', $digest);
    }

    public function testGetSubject()
    {
        $csr = file_get_contents(__DIR__.'/fixtures/csr');
        $info = $this->csr->getSubject($csr);
        $this->assertContains('nat at fishtrap co uk', $info);
    }

    public function testExtractCSRInfo()
    {
        $csr = file_get_contents(__DIR__.'/fixtures/csr');
        $this->csr->extractCSRInfo($csr);
        $line = "        Subject: C=UK, ST=South Yorkshire, L=Sheffield, O=Fishtrap, CN=Fishtrap/emailAddress=nat at fishtrap co uk";
        $this->assertContains($line, $this->csr->getCSRRawInfo());
    }

    public function testGetPublicKey()
    {
        $csr = file_get_contents(__DIR__.'/fixtures/csr');
        $key = $this->csr->getPublicKey($csr);
        $this->assertContains(hex2bin('010001'), $key['rsa']);
    }
}