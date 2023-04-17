<?php

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/profile.tpl.php');

if (!$session->isLoggedIn()) die(header('Location: /'));

if (!isset($_GET['id'])) {
    die(header('Location: main_page.php'));
}

$id = intval($_GET['id']);
$db = getDatabaseConnection();

if ($id != $session->getId()) {
    die(header('Location: /'));
}

$user = Client::getById($db, $id);
if (!$user) {
    die(header('Location: /'));
}
// $type = Client::getType($db, $id);



output_header();
drawProfileForm($user);
output_footer();

?>
