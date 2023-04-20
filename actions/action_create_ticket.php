<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/main_page.php'));
}
require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../utils/validate.php');

$title = $_POST['title'];
$description = $_POST['description'];

// this assumes each hashtagID is valid, but we have to check it.
$hashtags = $_POST['hashtags'] != NULL ? array_map('intval', $_POST['hashtags']) : array();

var_dump($hashtags);

$departmentID = empty($_POST['department']) ? NULL : intval($_POST['department']); /* Department can be null */
$priority = $_POST['priority'] ?? NULL; /* priority can be null (atm is always null) */

if (!is_valid_string($title) || !is_valid_string($description)) {
    die(header('Location: ../pages/create_ticket.php'));
}

$userID = $session->getId();     /* session userID */
$username = $session->getName();  /* session username */


if (Ticket::existsTicket($db, $title, $userID)) {   /* Could send to ticket page of the existing ticket */
    $session->addMessage('error', "Ticket with the same title already exists");
    die(header('Location: ../pages/create_ticket.php'));
}

/* status is "open", submit date is now, agent always null: defined inside createTicket */
$id = Ticket::createTicket($db, $title, $userID, $priority, $hashtags, $description, $departmentName);
// $session->addMessage('success', "Ticket created successfully");

header('Location: ../pages/individual_ticket.php?id=' . $id);

?>
