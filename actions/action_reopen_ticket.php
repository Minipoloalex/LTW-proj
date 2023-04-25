<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../utils/validate.php');

$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: ../pages/landing_page.php'));
// TODO: csrf check


$db = getDatabaseConnection();

if (!is_valid_id($_POST['ticketID'])) die(header('Location: ../pages/main_page.php'));
$ticketID = intval($_POST['ticketID']);
$userID = $session->getId();
if (!Client::hasAcessToTicket($db, $userID, $ticketID)) die(header('Location: ../pages/main_page.php'));


Ticket::updateStatus($db, $ticketID, 'open');   // TODO: open or in progress?

header('Location: ../pages/individual_ticket.php?id=' . $ticketID)
?>
