<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: landing_page.php'));

require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../utils/validate.php');

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/individual_ticket.tpl.php');

require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/action.class.php');


if (!is_valid_id($_GET['id'])) {
    die(header('Location: main_page.php'));
}

$id = intval($_GET['id']);


// validate if current user has access to ticket given by id
// if not, redirect to home page (or login page if not logged in)

$ticket = Ticket::getById($db, $id);
if (!$ticket) {
    die(header('Location: main_page.php'));
}

$messages = Message::getByTicket($db, $id);
$actions = Action::getByTicket($db, $id);
$hashtags = Hashtag::getHashtags($db);
$agents = Agent::getAgents($db);
$departments = Department::getDepartments($db);


var_dump($actions);

output_header();
output_single_ticket($ticket, $messages, $actions, $hashtags, $agents, $departments);
output_message_form($ticket->ticketid);
output_footer();

?>
