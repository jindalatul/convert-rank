<?php
session_set_cookie_params([
  'path' => '/',
  'secure' => false,       // true if HTTPS
  'httponly' => true,
  'samesite' => 'Lax',
]);
session_start();

// Path to env file at project root
$envPath = dirname(__DIR__) . '/env/google-login-env.php';

if (!file_exists($envPath)) {
    error_log('DB env file missing: ' . $envPath);
    return null;
}
$env = require $envPath;

$GOOGLE_CLIENT_ID     = $env['GOOGLE_CLIENT_ID'];
$GOOGLE_CLIENT_SECRET = $env['GOOGLE_CLIENT_SECRET'];
$GOOGLE_REDIRECT_URI  = $env['GOOGLE_REDIRECT_URI'];
$HOST_NAME            = $env['HOST_NAME'];

$GOOGLE_AUTH_SCOPES = ['openid','email','profile'];
$GOOGLE_AUTH_ENDPOINT  = 'https://accounts.google.com/o/oauth2/v2/auth';
$GOOGLE_TOKEN_ENDPOINT = 'https://oauth2.googleapis.com/token';
$GOOGLE_USERINFO_URL   = 'https://www.googleapis.com/oauth2/v3/userinfo';

// Helper functions
function secure_random(int $len = 32): string {
    return rtrim(strtr(base64_encode(random_bytes($len)), '+/', '-_'), '=');
}
function http_build_query_utf8(array $p): string {
    return http_build_query($p, '', '&', PHP_QUERY_RFC3986);
}
function curl_post_form(string $url, array $fields): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query_utf8($fields),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_TIMEOUT        => 20,
    ]);
    $resp = curl_exec($ch);
    $err  = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$resp, $err, $code];
}
function curl_get_bearer(string $url, string $token): array {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$token],
        CURLOPT_TIMEOUT        => 20,
    ]);
    $resp = curl_exec($ch);
    $err  = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$resp, $err, $code];
}
