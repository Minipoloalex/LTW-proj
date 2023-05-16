<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: landing_page.php'));

// $userID = $session->getId();
// if (!Client::isAdmin($db, $userID)) die(header('Location: main_page.php'));

require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/users.tpl.php');
require_once(__DIR__ . '/../templates/cards.tpl.php');

$count = 100; /* limite de users na lista */

$users = Client::getClients($db, $count);
$type = Client::getType($db, $session->getId());
$all_departments = Department::getDepartments($db);
$filters = Client::getFilters($db);
output_header($session, $type);
drawTitle("Users", "user");
drawUsersFilterMenu($filters);
// drawUsersTable($users, $db, $all_departments);
drawCardContainer();
output_footer();

?>

