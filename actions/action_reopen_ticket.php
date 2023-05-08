<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');

$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: ../pages/landing_page.php'));
if (!$session->verifyCsrf($_POST['csrf'] ?? '')) die(header('Location: ../pages/create_ticket.php'));

$db = getDatabaseConnection();

if (!is_valid_id($_POST['ticketID'])) die(header('Location: ../pages/main_page.php'));
$ticketID = intval($_POST['ticketID']);

$ticket = Ticket::getById($db, $ticketID);
if ($ticket === null) die(header('Location: ../pages/main_page.php'));

if (!Client::hasAcessToTicket($db, $session->getId(), $ticketID)) die(header('Location: ../pages/main_page.php'));

$status = $ticket->assignedagent !== NULL ? 'in progress' : 'open';
Ticket::updateStatus($db, $ticketID, $status);

header('Location: ../pages/individual_ticket.php?id=' . $ticketID)
?>
