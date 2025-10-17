<?php
require __DIR__ . '/config.php';

// At top of google-login.php
//error_log("login session id: " . session_id());

$state = secure_random(16);
$_SESSION['oauth2_state'] = $state;

$params = [
  'client_id'     => $GOOGLE_CLIENT_ID,
  'redirect_uri'  => $GOOGLE_REDIRECT_URI,
  'response_type' => 'code',
  'scope'         => 'openid email profile',
  'state'         => $state,
];

$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' .
           http_build_query($params, '', '&', PHP_QUERY_RFC3986);

header('Location: ' . $authUrl);
exit;
