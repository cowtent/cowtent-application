# How to Use Rest Api

## Create an application

To use Rest Api, you need first to create an application, restricted to a workspace or not.
This will generate both an **Api Key** and a **Secret**.

## Call Rest Api using WSSE headers

Please note this sample code will change the secret key.
So this script can't be run twice because the second time authentication won't work.

The new secret will be returned by the Rest call and is available in the application view screen.

```php
<?php

$username = 'preprod-724';
$apiKey   = '03b74930-46d2-11e5-8066-080027b9a9d8';
$secret   = 'stopi9ddno08oso4c8css8goc8cgkks';
$security = $apiKey . '{' . $secret . '}';

$nonce   = uniqid();
$created = date('c');
$digest  = base64_encode(sha1(base64_decode($nonce) . $created . $security, true));

$headers   = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: WSSE profile="UsernameToken"';
$headers[] =
    sprintf(
        'X-WSSE: UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
        $username,
        $digest,
        $nonce,
        $created
    );

$data = array(
    'api_key' => $apiKey,
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://lolcahost/app_dev.php/api/application/resetSecret');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$result = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo 'HTTP Return code:' . $status . '\n';

if (false === $result) {
    echo 'ERROR: ' . curl_error($ch) . '\n';
} else {
    echo 'RESULT: \n';
    var_dump($result);
}
```
