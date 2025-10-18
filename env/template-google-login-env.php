<?php
// app/env.php — holds private keys
/*
Generate credentials here
https://console.cloud.google.com/apis/credentials
*/
return [
    'GOOGLE_CLIENT_ID'     => '',
    'GOOGLE_CLIENT_SECRET' => '',
    'GOOGLE_REDIRECT_URI'  => 'http://localhost/convert-rank/app/google-callback.php',
    'HOST_NAME'            => 'http://localhost/convert-rank/app'
];
?>
