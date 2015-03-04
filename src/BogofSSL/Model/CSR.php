<?php

namespace BogofSSL\Model;

class CSR
{
    private $digest;

    private $csrinfo;

    public function extractCSRInfo($csr)
    {
        $tmpfname = tempnam("/tmp", 'csr.');
        $handle = fopen($tmpfname, "w");
        fwrite($handle, $csr);
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

    public function getCSRRawInfo()
    {
        return $this->csrinfo;
    }

    public function getSubject($csr)
    {
        return openssl_csr_get_subject($csr, false);
    }

    public function getPublicKey($csr)
    {
        $pubKey = openssl_csr_get_public_key($csr);
        return openssl_pkey_get_details($pubKey);
    }
}