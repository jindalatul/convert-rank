<?php 
require __DIR__ . '/config.php';

// DEBUG (temporarily): 
// error_log("index session id: ".session_id());
// error_log("index session keys: ".json_encode(array_keys($_SESSION)));

if (empty($_SESSION['user_id']) && empty($_SESSION['google_user'])) {
    require __DIR__ . '/login-html-page.php';
    loginFormHtml($HOST_NAME);
    exit;
}

$name    = $_SESSION['user_name']    ?? ($_SESSION['google_user']['name']  ?? $_SESSION['google_user']['email'] ?? 'User');
$picture = $_SESSION['user_picture'] ?? ($_SESSION['google_user']['picture'] ?? '');
$sub     = $_SESSION['google_user']['sub']   ?? '';
$email   = $_SESSION['google_user']['email'] ?? '';
$verified= !empty($_SESSION['google_user']['email_verified']);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>My Account</title></head>
<body>
  <h1>Welcome, <?php echo htmlspecialchars($name); ?></h1>
  <?php if ($picture): ?>
    <img alt="Avatar" src="<?php echo htmlspecialchars($picture); ?>" width="96" height="96">
  <?php endif; ?>
  <ul>
    <li>ID: <?php echo htmlspecialchars($sub); ?></li>
    <li>Email: <?php echo htmlspecialchars($email); ?></li>
    <li>Verified: <?php echo $verified ? 'yes' : 'no'; ?></li>
  </ul>
  <a href="<?php echo $HOST_NAME; ?>/logout.php">Sign out</a>
</body>
</html>
