<?php
require __DIR__ . '/config.php';

// At top of google-callback.php
//error_log("callback session id: " . session_id());

if (!isset($_GET['state'], $_GET['code'])) {
  http_response_code(400); echo 'Missing state or code'; exit;
}
if (empty($_SESSION['oauth2_state']) || $_GET['state'] !== $_SESSION['oauth2_state']) {
  http_response_code(400); echo 'Invalid state'; exit;
}
unset($_SESSION['oauth2_state']);

[$raw, $err, $status] = curl_post_form($GOOGLE_TOKEN_ENDPOINT, [
  'code'          => $_GET['code'],
  'client_id'     => $GOOGLE_CLIENT_ID,
  'client_secret' => $GOOGLE_CLIENT_SECRET,
  'redirect_uri'  => $GOOGLE_REDIRECT_URI,
  'grant_type'    => 'authorization_code',
]);

if ($err) { http_response_code(502); echo 'Token error: '.htmlspecialchars($err); exit; }

$token = json_decode($raw, true);
if ($status !== 200 || empty($token['access_token'])) {
  http_response_code(502); echo 'Bad token response: '.htmlspecialchars($raw); exit;
}

$_SESSION['google_access_token']  = $token['access_token'];
$_SESSION['google_refresh_token'] = $token['refresh_token'] ?? null;
$_SESSION['google_id_token']      = $token['id_token'] ?? null;
$_SESSION['google_token_expires'] = time() + (int)($token['expires_in'] ?? 0);

[$uRaw, $uErr, $uStatus] = curl_get_bearer($GOOGLE_USERINFO_URL, $token['access_token']);
if ($uErr) { http_response_code(502); echo 'Userinfo error: '.htmlspecialchars($uErr); exit; }

$user = json_decode($uRaw, true);
if ($uStatus !== 200 || !is_array($user)) {
  http_response_code(502); echo 'Bad userinfo: '.htmlspecialchars($uRaw); exit;
}

$_SESSION['google_user'] = $user;
header('Location: '.$HOST_NAME.'/index.php'); exit;
