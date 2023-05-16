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
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/departments.tpl.php');
require_once(__DIR__ . '/../templates/cards.tpl.php');


$departments = Department::getDepartments($db);

output_header($session);
drawTitle("Departments", "department");
drawDepartmentsTable($departments, $db);
drawCardContainer();
output_footer();

?>
