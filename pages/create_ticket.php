<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../templates/create_ticket.tpl.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/landing_page.php'));
}
$db = getDatabaseConnection();

output_header();
output_create_ticket_form($db, $session);
output_footer();

?>
