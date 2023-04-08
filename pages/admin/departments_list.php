<?php
declare(strict_types=1);
require_once(__DIR__ . '/../../database/connection.db.php');
require_once(__DIR__ . '/../../utils/session.php');
require_once(__DIR__ . '/../../database/client.class.php');
require_once(__DIR__ . '/../../database/department.class.php');
require_once(__DIR__ . '/../../database/ticket.class.php');
require_once(__DIR__ . '/../../database/agent.class.php');
require_once(__DIR__ . '/../../templates/common.tpl.php');
require_once(__DIR__ . '/../../templates/departments.tpl.php');

$db = getDatabaseConnection();
$departments = Department::getDepartments($db);

output_header();
drawDepartmentsTable($departments, $db);
output_footer();

?>
