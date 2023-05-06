<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
}
// TODO: csrf check
$db = getDatabaseConnection();

if (!is_valid_ticket_id($db, $_POST['ticketID'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing ticketID parameter'));
    exit();
}
if (!is_valid_string($_POST['status']) || !is_valid_status($_POST['status'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing status parameter'));
    exit();
}
$ticketID = intval($_POST['ticketID']);
$status = $_POST['status'];
$userID = $session->getId();

if (!Client::hasAcessToTicket($db, $userID, $ticketID)) {
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'User does not have access to ticket'));
    exit();
}

Ticket::updateStatus($db, $ticketID, $status);
$ticket = Ticket::getById($db, $ticketID);
if (!$ticket) {
    http_response_code(500); // Internal server error
    echo json_encode(array('error' => 'Ticket does not exist'));
    exit();
}

http_response_code(200); // OK
echo json_encode(array(
    'success' => 'Ticket status updated',
    'ticketID' => $ticket->ticketid,
    'department' => $ticket->departmentName,
    'status' => $ticket->status,
    'agent' => $ticket->assignedagent,
    'priority' => $ticket->priority,
    'hashtags' => array_column($ticket->hashtags, 'hashtagname')
));

?>
