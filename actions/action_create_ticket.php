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

if (!is_valid_string($title) || !is_valid_string($description)) {
    die(header('Location: ../pages/create_ticket.php'));
}

$hashtags = $_POST['hashtags'] ?? array();
if ($hashtags != NULL) {
    if (!is_valid_array_ids($hashtags)) die(header('Location: ../pages/create_ticket.php'));    // TODO: verify if this works
}
// have to verify that all ids are valid ids (i.e. correspond to existing hashtags)
$hashtags = array_map('intval', $hashtags);


$departmentID = empty($_POST['department']) ? NULL : intval($_POST['department']); /* Department can be null */
$priority = $_POST['priority'] ?? NULL; /* priority can be null (atm is always null) */

$userID = $session->getId();     /* session userID */
$username = $session->getName();  /* session username */


if (Ticket::existsTicket($db, $title, $userID)) {
    $session->addMessage('error', "Ticket with the same title already exists");
    die(header('Location: ../pages/create_ticket.php'));
}

/* status is "open", submit date is now, agent always null: defined inside createTicket */
$id = Ticket::createTicket($db, $title, $userID, $priority, $hashtags, $description, $departmentID);
// $session->addMessage('success', "Ticket created successfully");

header('Location: ../pages/individual_ticket.php?id=' . $id);

?>
