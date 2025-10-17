<?php
// app/env.php — holds private keys
/*
Generate credentials here
https://console.cloud.google.com/apis/credentials
*/
return [
    'GOOGLE_CLIENT_ID'     => '390929839517-0dqaic9a51nqr2c22k1p7m82appckd6d.apps.googleusercontent.com',
    'GOOGLE_CLIENT_SECRET' => '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',
    'GOOGLE_REDIRECT_URI'  => 'http://localhost/convert-rank/app/google-callback.php',
    'HOST_NAME'            => 'http://localhost/convert-rank/app'
];
?>
