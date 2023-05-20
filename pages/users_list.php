<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/users.tpl.php');
require_once(__DIR__ . '/../templates/cards.tpl.php');
$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: landing_page.php'));
$userID = $session->getId();
$db = getDatabaseConnection();
if (!Client::isAdmin($db, $userID)) {
    die(header('Location: main_page.php'));
}
$type = Client::getType($db, $session->getId());
$filters = Client::getFilters($db);
output_header($session, $type);
drawTitle("Users", "user");
drawUsersFilterMenu($filters);
drawCardContainer();
output_footer();
?>
