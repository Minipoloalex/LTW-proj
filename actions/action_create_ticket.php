<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/main_page.php'));
}
if (!$session->verifyCsrf($_POST['csrf'] ?? '')) {
    die(header('Location: ../pages/create_ticket.php'));
}

$db = getDatabaseConnection();

$title = $_POST['title'];
$description = $_POST['description'];

if (!is_valid_title($title)) {
    $session->addMessage('error', "Invalid title. Title must be between 1 and 25 characters long");
    die(header('Location: ../pages/create_ticket.php'));
}
if (!is_valid_string($description)) {
    $session->addMessage('error', "Invalid description. A description is required.");
    die(header('Location: ../pages/create_ticket.php'));
}

$hashtags = $_POST['hashtags'] ?? array();
if (!is_valid_array_hashtag_ids($db, $hashtags)) {
    $session->addMessage('error', "There was an invalid hashtag");
    die(header('Location: ../pages/create_ticket.php'));
}

$hashtags = array_map('intval', array_unique($hashtags));


if (is_valid_string($departmentID) && !is_valid_department_id($db, $_POST['department'])) {
    // can be null
    // but if it's not null, then must be valid
    $session->addMessage('error', "Invalid department");
    die(header('Location: ../pages/create_ticket.php'));
}
$departmentID = empty($_POST['department']) ? NULL : intval($_POST['department']); /* Department can be null */

if (!is_valid_priority($_POST['priority'])) {
    $session->addMessage('error', "Invalid priority");
    die(header('Location: ../pages/create_ticket.php'));
}
$priority = $_POST['priority'];

$userID = $session->getId();     /* session userID */
$username = $session->getName();  /* session username */


if (Ticket::existsTicket($db, $title, $userID)) {
    $session->addMessage('error', "You already have a ticket with the same title");
    die(header('Location: ../pages/create_ticket.php'));
}

/* status is "open", submit date is now, agent always null: defined inside createTicket */
$id = Ticket::createTicket($db, $title, $userID, $priority, $hashtags, $description, $departmentID);
$session->addMessage('success', "Ticket created successfully");

header('Location: ../pages/individual_ticket.php?id=' . $id);

?>
