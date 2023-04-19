<?php
require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../landing_page.php'));
}
require_once(__DIR__ . '/../templates/tickets.tpl.php');


output_header();

output_footer();

?>
