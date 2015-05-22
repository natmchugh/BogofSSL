<?php

namespace BogofSSL\Model;

class CSR
{
    private $digest;

    private $csrinfo;

    private $privkey;

    private $iacert;

    private $csrASN1;

    public function __construct($csr, $privkey, $iacert)
    {
        $this->csrASN1 = $csr;
        $this->privkey = $privkey;
        $this->iacert = $iacert;
    }

    public function extractCSRInfo()
    {
        $tmpfname = tempnam("/tmp", 'csr.');
        $handle = fopen($tmpfname, "w");
        fwrite($handle, $this->csrASN1);
        fclose($handle);

        $escaped_command = escapeshellcmd("openssl req -in $tmpfname -noout -text");
        exec($escaped_command, $csrinfo);
        $this->csrinfo = $csrinfo;
    }

    public function extractDigest($csrdata = array())
    {
        foreach ($csrdata as $line) {
            if (preg_match('/Signature Algorithm: (.+)WithRSA/', $line, $matches))
            return $matches[1];
        }
      
    }

    public function signRequest()
    {
        $config = [
            'digest_alg' => 'md5',
            'x509_extensions' => 'v3_req',
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            "encrypt_key" => false,
        ];
        $serialNumber = $this->getNewSerialNumber();
        $usercert = openssl_csr_sign($this->csrASN1, $this->iacert, $this->privkey, 365, $config, $serialNumber);
        openssl_x509_export($usercert, $certout);
        // $filename = tempnam(__DIR__.'/../signatures/', 'csr');
        // $csr = ['certout' => $certout, 'csr' => $this->csrASN1]; 
        // file_put_contents($filename, json_encode($csr));
        return $certout;
    }

    public function getCSRRawInfo()
    {
        return $this->csrinfo;
    }

    public function getSubject()
    {
        return openssl_csr_get_subject($this->csrASN1, false);
    }

    public function getPublicKey()
    {
        $pubKey = openssl_csr_get_public_key($this->csrASN1);
        return openssl_pkey_get_details($pubKey);
    }

    public function getErrors()
    {
        $errors = [];
        while (($e = openssl_error_string()) !== false) {
            $errors[] = $e;
        }
        return $errors;
    }

    public function getNewSerialNumber()
    {
        mt_srand(time());
        return mt_rand();
    }
}