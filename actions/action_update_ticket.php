<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');

$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: landing_page.php'));
// csrf check (?)

require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/department.class.php');

require_once(__DIR__ . '/../utils/validate.php');

if (!is_valid_id($_POST['ticketid'])) die(header('Location: main_page.php'));
$ticketID = intval($POST['ticketID']);

if (!Client::canChangeTicketInfo($db, $session->getId(), $ticketID)) {
    die(header('Location: main_page.php'));
}

$departmentID = empty($_POST['department']) ? NULL : intval($_POST['department']);
$agentID = empty($_POST['agent']) ? NULL : intval($_POST['agent']);

$_POST['hashtags'] = array_map('intval', $_POST['hashtags']);
$priority = $_POST['priority'];

$ticket = Ticket::getById($db, $ticketID);
$ticket->updateTicket($db, $departmentID, $agentID, $priority, $hashtags);
header('Location: ../pages/individual_ticket.php?id=' . $ticketID)
?>
