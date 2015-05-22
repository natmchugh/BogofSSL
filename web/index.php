<?php
require '../vendor/autoload.php';

use BogofSSL\Model\CSR;

// $configPaths = sprintf(
//     '%s/config/{,*.}{global,%s,local}.php', 
//     APPLICATION_PATH,
//     SLIM_MODE
// );

// $configPaths = sprintf(
//     '%s/config/{,*.}{global,%s,local}.php', 
//     APPLICATION_PATH,
//     SLIM_MODE
// );
// $config = Zend\Config\Factory::fromFiles(glob($configPaths, GLOB_BRACE));

$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\SessionCookie());
$app->config(array(
    'debug' => true,
    'templates.path' => '../templates'
));

// $config = Zend\Config\Factory::fromFiles(glob($configPaths, GLOB_BRACE));

$app->get('/', function () use ($app) {
    $app->render('csr_form.php');
});

$caArgs = [
    'digest_alg' => 'md5',
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
    'encrypt_key' => false,
];

$iaKey = 'file://'.__DIR__.'/../certificates/ia.key';
$iaCert = 'file://'.__DIR__.'/../certificates/ia.crt';

$app->post('/csr_info', function () use ($app, $iaKey, $iaCert) {

    $csrData = $app->request->post('csr');
    $csr = new CSR($csrData, $iaKey, $iaCert);
    $csr->extractCSRInfo();
    $csrInfo = $csr->getCSRRawInfo();
    $digest = $csr->extractDigest($csrInfo);

    if (!$digest) {
        $app->render('csr_error.php', ['csr' => $csrData]);
        return;
    }

    $subject = $csr->getSubject();
    $key = $csr->getPublicKey();

    try {
        $cert = $csr->signRequest();
    } catch (Exception $e) {
        $errors = $csr->getErrors();
        error_log($errors);
        die('Something went wrong');
    }

    $app->render('csr_info.php', ['subject' => $subject, 'digest' => $digest, 'key' => $key, 'certificate' => $cert]);
});

$app->run();