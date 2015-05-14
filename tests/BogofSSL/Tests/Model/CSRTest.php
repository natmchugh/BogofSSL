<?php

namespace BogofSSL\Tests\Model;

use BogofSSL\Model\CSR;

class CSRTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $csr = file_get_contents(__DIR__.'/fixtures/csr');
        $privKey = file_get_contents(__DIR__.'/../../../../certificates/ca.key');
        $caCert = file_get_contents(__DIR__.'/../../../../certificates/ca.crt');
        $this->csr = new CSR($csr, $privKey, $caCert);
    }

    public function testExtractDigest()
    {
        include(__DIR__.'/fixtures/csr_info.array');
        $digest = $this->csr->extractDigest($csrInfo);
        $this->assertSame('mdc2', $digest);
    }

    public function testGetSubject()
    {
        $info = $this->csr->getSubject();
        $this->assertContains('nat at fishtrap co uk', $info);
    }

    public function testExtractCSRInfo()
    {
        $this->csr->extractCSRInfo();
        $line = "        Subject: C=UK, ST=South Yorkshire, L=Sheffield, O=Fishtrap, CN=Fishtrap/emailAddress=nat at fishtrap co uk";
        $this->assertContains($line, $this->csr->getCSRRawInfo());
    }

    public function testGetPublicKey()
    {
        $key = $this->csr->getPublicKey();
        $this->assertContains(hex2bin('010001'), $key['rsa']);
    }

    public function testSignRequest()
    {
        $cert = $this->csr->signRequest();
        $endFlag = '-----END CERTIFICATE-----';
        $this->assertContains($endFlag, $cert);
    }

    public function testGetNewSerialNuber()
    {
        $serialNumber = $this->csr->getNewSerialNumber();
        $this->assertTrue(is_int($serialNumber));
    }
}