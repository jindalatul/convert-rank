<?php
require __DIR__ . '/config.php';
$db_connection = dirname(__DIR__) . '/db/connection.php';     // this holds database connection function
require_once $db_connection;

//var_dump($db_connection); die();
// -----------------------------------------------------------------------------
// Validate OAuth state and code
// -----------------------------------------------------------------------------
if (!isset($_GET['state'], $_GET['code'])) {
    http_response_code(400);
    exit('Missing state or code');
}

if (empty($_SESSION['oauth2_state']) || $_GET['state'] !== $_SESSION['oauth2_state']) {
    http_response_code(400);
    exit('Invalid state');
}
unset($_SESSION['oauth2_state']);

// -----------------------------------------------------------------------------
// Exchange authorization code for access token
// -----------------------------------------------------------------------------
[$raw, $err, $status] = curl_post_form($GOOGLE_TOKEN_ENDPOINT, [
    'code'          => $_GET['code'],
    'client_id'     => $GOOGLE_CLIENT_ID,
    'client_secret' => $GOOGLE_CLIENT_SECRET,
    'redirect_uri'  => $GOOGLE_REDIRECT_URI,
    'grant_type'    => 'authorization_code',
]);

if ($err) {
    http_response_code(502);
    exit('Token request error: ' . htmlspecialchars($err));
}

$token = json_decode($raw, true);
if ($status !== 200 || empty($token['access_token'])) {
    http_response_code(502);
    exit('Bad token response: ' . htmlspecialchars($raw));
}

// Save token data in session (optional)
$_SESSION['google_access_token']  = $token['access_token'];
$_SESSION['google_refresh_token'] = $token['refresh_token'] ?? null;
$_SESSION['google_id_token']      = $token['id_token'] ?? null;
$_SESSION['google_token_expires'] = time() + (int)($token['expires_in'] ?? 0);

// -----------------------------------------------------------------------------
// Fetch user info from Google
// -----------------------------------------------------------------------------
[$uRaw, $uErr, $uStatus] = curl_get_bearer($GOOGLE_USERINFO_URL, $token['access_token']);
if ($uErr) {
    http_response_code(502);
    exit('Userinfo request error: ' . htmlspecialchars($uErr));
}

$user = json_decode($uRaw, true);
if ($uStatus !== 200 || !is_array($user) || empty($user['sub'])) {
    http_response_code(502);
    exit('Invalid userinfo: ' . htmlspecialchars($uRaw));
}

// -----------------------------------------------------------------------------
// Connect to your database
// -----------------------------------------------------------------------------
$conn = getDbConnection();
if (!$conn) {
    http_response_code(500);
    exit('Database connection failed');
}

// Escape user data safely
$sub   = $conn->real_escape_string($user['sub']);
$name  = $conn->real_escape_string($user['name'] ?? '');
$email = $conn->real_escape_string($user['email'] ?? '');
$pic   = $conn->real_escape_string($user['picture'] ?? '');

// -----------------------------------------------------------------------------
// Check if the user already exists (by Google sub or email)
// -----------------------------------------------------------------------------
$sql = "SELECT id, status, membership_tier 
        FROM users 
        WHERE google_sub = '$sub' OR email = '$email' 
        LIMIT 1";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    // ✅ Existing user
    $row = $res->fetch_assoc();
    $user_id = $row['id'];
    $status  = $row['status'];
    $tier    = $row['membership_tier'];

    // Update profile info (optional)
    $conn->query("UPDATE users 
                  SET google_sub = '$sub', name = '$name', email = '$email', picture = '$pic' 
                  WHERE id = $user_id");
} else {
    // 🆕 New user (defaults)
    $status = 'enabled';
    $tier   = 'free';
    $insert = "INSERT INTO users 
              (google_sub, name, email, picture, status, membership_tier)
              VALUES ('$sub', '$name', '$email', '$pic', '$status', '$tier')";
    if ($conn->query($insert)) {
        $user_id = $conn->insert_id;
    } else {
        error_log('DB insert error: ' . $conn->error);
        http_response_code(500);
        exit('Database insert error');
    }
}

// -----------------------------------------------------------------------------
// Store session data
// -----------------------------------------------------------------------------
// after you fetched $user from Google and (optionally) wrote to DB
$_SESSION['user_id']      = $user_id ?? null;                   // if you have DB id
$_SESSION['user_name']    = $user['name'] ?? ($user['email'] ?? 'User');
$_SESSION['user_picture'] = $user['picture'] ?? '';
$_SESSION['google_user']  = $user;   // keep this for your existing index check

// -----------------------------------------------------------------------------
// Handle disabled users
// -----------------------------------------------------------------------------
if ($status !== 'enabled') {
    session_destroy();
    exit('Your account is disabled. Please contact support.');
}

// -----------------------------------------------------------------------------
// Redirect to homepage
// -----------------------------------------------------------------------------
header('Location: '.$HOST_NAME.'/index.php'); exit;
exit;
