<?php
declare(strict_types=1);
require_once(__DIR__ . '/../../database/connection.db.php');

require_once(__DIR__ . '/../../utils/session.php');

require_once(__DIR__ . '/../../database/client.class.php');
require_once(__DIR__ . '/../../templates/common.tpl.php');
require_once(__DIR__ . '/../../templates/users.tpl.php');

$count = 100; /* limite de users na lista */
$db = getDatabaseConnection();
$users = Client::getClients($db, $count);

output_header();
drawUsersTable($users, $db);
output_footer();

?>

