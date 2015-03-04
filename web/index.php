<?php
require '../vendor/autoload.php';

use BogofSSL\Model\CSR;

$app = new \Slim\Slim();
$app->add(new \Slim\Middleware\SessionCookie());
$app->config(array(
    'debug' => true,
    'templates.path' => '../templates'
));

$app->get('/', function () use ($app) {
    $app->render('csr_form.php');
});


$app->post('/csr_info', function () use ($app) {

    $subject = $key = null;
    $csr = new CSR();
    $csrData = $app->request->post('csr');
    $csr->extractCSRInfo($csrData);
    $csrInfo = $csr->getCSRRawInfo();
    $digest = $csr->extractDigest($csrInfo);

    if (!$digest) {
        $app->render('csr_error.php', ['csr' => $csrData]);
        return;
    }

    $subject = $csr->getSubject($csrData);
    $key = $csr->getPublicKey($csrData);
    $app->render('csr_info.php', ['subject' => $subject, 'digest' => $digest, 'key' => $key]);
});

$app->run();