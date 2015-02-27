<html>

    <h1>BOGOF SSL</h1>
    <h2>Buy One Get One Free!</h2>
    <p>
    Our certificates are 99.9%<super>&#42;</super> untrusted by all major (and minor) browsers.
    </p>

    <p>
        We use the tried and trusted digest algorithms MD5 and MD4 only.
    </p>

    <p>
        Feel free to put this padloack all over your site it means very little.
    </p>

    <form method="POST" action="#">

    <label>Paste your Certificate Signing Request (.csr) file</label>

    <p>
    <textarea name="csr" rows="30" cols="100"><?php if(isset($_POST['csr'])) echo $_POST['csr']; ?></textarea>
    </p>

    <input type="submit" >
    </form>

    <super>&#42;</super>&#177;0.1%


</html>

<?php

if (isset($_POST['csr'])) {

    $csrdata = $_POST['csr'];

    $tmpfname = tempnam("/tmp", 'csr.');
    $handle = fopen($tmpfname, "w");
    fwrite($handle, $csrdata);
    fclose($handle);

    $cacert = "file:///Users/nmchugh/colliding_cert/ca.crt";
    $privkey = array("file:///Users/nmchugh/colliding_cert/ca.key", '');

    $escaped_command = escapeshellcmd("openssl req -in $tmpfname -noout -text");
     exec($escaped_command, $csrinfo);

    $digest = getDigestAlgo($csrinfo);

    var_dump('digest', $digest);

    $usercert = openssl_csr_sign($csrdata, $cacert, $privkey, 730);
    openssl_x509_export($usercert, $certout);
    echo '<pre>'.$certout.'</pre>';

    unlink($tmpfname);
}

function getDigestAlgo($csrdata)
{
    foreach ($csrdata as $line) {
        if (preg_match('/Signature Algorithm: (.+)WithRSA/', $line, $matches))
        return $matches[1];
    }
}

?>
