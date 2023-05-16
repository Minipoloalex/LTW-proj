<?php
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../templates/tickets.tpl.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();
$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../landing_page.php'));
}
$type = Client::getType($db, $session->getId());
if ($type === 'Client') {
    die(header('Location: ../main_page.php'));
}

output_header($session, $type);

output_footer();

?>
