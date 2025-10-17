<?php 
require __DIR__ . '/config.php';

if (empty($_SESSION['google_user'])) 
{
    require __DIR__ . '/login-html-page.php';
    loginFormHtml($HOST_NAME);
    exit;
}
$user = $_SESSION['google_user'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>My Account</title></head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($user['name'] ?? $user['email'] ?? 'User'); ?></h1>
    <?php if (!empty($user['picture'])): ?>
        <img alt="Avatar" src="<?php echo htmlspecialchars($user['picture']); ?>" width="96" height="96">
    <?php endif; ?>
    <ul>
        <li>ID: <?php echo htmlspecialchars($user['sub'] ?? ''); ?></li>
        <li>Email: <?php echo htmlspecialchars($user['email'] ?? ''); ?></li>
        <li>Verified: <?php echo !empty($user['email_verified']) ? 'yes' : 'no'; ?></li>
    </ul>

    <a href="<?php echo $HOST_NAME;?>/logout.php">Sign out</a>
</body>
</html>
