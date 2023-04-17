<?php
declare(strict_types=1);

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/individual_ticket.tpl.php');

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/action.class.php');


$id = intval($_GET['id']);
$db = getDatabaseConnection();

// validate if current user has access to ticket given by id
// if not, redirect to home page (or login page if not logged in)

$ticket = Ticket::getById($db, $id);
$messages = Message::getByTicket($db, $id);
$actions = Action::getByTicket($db, $id);
var_dump($actions);

output_header();
output_single_ticket($ticket, $messages, $actions);
output_footer();

?>
