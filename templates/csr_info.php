
<span ><?= isset($flash['error']) ? $flash['error'] : '' ?></span>

<ul>

<li>
Country Name: <?= $subject['countryName'] ?>
</li>

<li>
State Or Province Name: <?= $subject['stateOrProvinceName'] ?>
</li>

<li>
Locality Name: <?= $subject['localityName'] ?>
</li>

<li>
Organization Name: <?= $subject['organizationName'] ?>
</li>

<li>
Common Name: <?= $subject['commonName'] ?>
</li>

<li>
Email Address: <?= $subject['emailAddress'] ?>
</li>

<li>
Digest: <?= $digest ?>
</li>

<li style="word-wrap:break-word;">
Modulus: <?= bin2hex($key['rsa']['n']); ?>
</li>

<li>
Exponent: <?= hexdec(bin2hex($key['rsa']['e'])); ?>
</li>

</ul>