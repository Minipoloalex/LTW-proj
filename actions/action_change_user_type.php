<?php
declare(strict_types=1);
/*
 * Updates related with Save button of users_list.php
 * for upgrading user type and for changing/assigning departments
 */

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/landing_page.php'));
}
if (!Client::isAdmin($db, $session->getId())) {
    die(header('Location: ../pages/main_page.php'));
}

// TODO: csrf check
require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/client.class.php');

require_once(__DIR__ . '/../utils/validate.php');

if (!isset($_POST['users'])) {
    die(header('Location: ../pages/main_page.php'));
}

$users = $_POST['users'];

?>
