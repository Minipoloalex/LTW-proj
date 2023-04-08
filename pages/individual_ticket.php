<?php
declare(strict_types=1);

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/individual_ticket.tpl.php');

require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$id = intval($_GET['id']);
$db = getDatabaseConnection();

// validate if current user has access to ticket given by id
// if not, redirect to home page (or login page if not logged in)

var_dump($id);
$ticket = Ticket::getById($db, $id);
var_dump($ticket);

output_header();
output_single_ticket($ticket);
output_footer();

?>
