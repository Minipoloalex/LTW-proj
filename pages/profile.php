<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/profile.tpl.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: landing_page.php'));
}
$db = getDatabaseConnection();

$user = Client::getById($db, $session->getId());
if (!$user) {
    die(header('Location: ../pages/main_page.php'));
}
$type = Client::getType($db, $user->id);

output_header($session, $type);
drawProfileForm($user, $session, $type);
output_footer();

?>
