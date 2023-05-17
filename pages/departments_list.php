<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');

require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/departments.tpl.php');
require_once(__DIR__ . '/../templates/cards.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: landing_page.php'));
}
$db = getDatabaseConnection();
if (!Client::isAdmin($db, $session->getId())) {
    die(header('Location: main_page.php'));
}

$departments = Department::getDepartments($db);

output_header($session, "Admin");
drawTitle("Departments", "department");
drawAddDepartmentForm();
// drawDepartmentsTable($departments, $db);
drawCardContainer();
output_footer();

?>
