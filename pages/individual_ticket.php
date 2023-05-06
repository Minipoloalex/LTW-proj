<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/individual_ticket.tpl.php');

require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/action.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: landing_page.php'));

$db = getDatabaseConnection();


if (!is_valid_ticket_id($db, $_GET['id'])) {
    die(header('Location: main_page.php'));
}
$id = intval($_GET['id']);


if (!Client::hasAcessToTicket($db, $session->getId(), $id)) {
    die(header('Location: main_page.php'));
}

$ticket = Ticket::getById($db, $id);
if (!$ticket) {
    die(header('Location: main_page.php'));
}

$messages = Message::getByTicket($db, $id);
$actions = Action::getByTicket($db, $id);
$hashtags = Hashtag::getHashtags($db);
$agents = Agent::getAgents($db);
$departments = Department::getDepartments($db);
$isAgentView = !($session->getName() === $ticket->username);

output_header();
output_single_ticket($ticket, $messages, $actions, $hashtags, $agents, $departments, $session, $isAgentView);
if (!$ticket->isClosed()) output_message_form($ticket->ticketid);

output_footer();

?>
