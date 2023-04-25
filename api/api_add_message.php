<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}

require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../utils/validate.php');

if (!is_valid_string($_POST['message'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing message parameter'));
    exit();
}
if (!is_valid_id($_POST['ticketID'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing ticketID parameter'));
    exit();
}
$message = $_POST['message'];
$ticketID = intval($_POST['ticketID']);
$userID = $session->getId();

if (!Client::hasAcessToTicket($db, $userID, $ticketID)) {
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'User does not have access to ticket'));
    exit();
}

$db = getDatabaseConnection();

$message = Message::addMessage($db, $userID, $ticketID, $message);

if (!$message) {
    http_response_code(500); // Internal server error
    echo json_encode(array('error' => 'Failed to add message to database'));
    exit();
}
echo json_encode(array(
    'id' => $message->id,
    'text' => $message->text,
    'userID' => $message->userID,
    'date' => date('F j', $message->date),
));
?>
