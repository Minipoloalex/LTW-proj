<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}
if (!$session->verifyCsrf($_POST['csrf'] ?? '')) {
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'CSRF token invalid', 'csrf' => $session->getCsrf(), 'post' => $_POST));
    exit();
}
$db = getDatabaseConnection();

$hashtags = $_POST['hashtags'] != NULL ? explode(',', $_POST['hashtags']) : array();

if (!is_valid_id($_POST['ticketID'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing ticketID parameter'));
    exit();
}
$ticketID = intval($_POST['ticketID']);

if (!Client::canChangeTicketInfo($db, $session->getId(), $ticketID)) {
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'User does not have access to ticket'));
    exit();
}

if (!is_valid_array_ids($hashtags)) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Hashtags are not valid'));
    exit();
}
$hashtagIDs = array_map('intval', $hashtags);

$departmentID = empty($_POST['department']) ? NULL : intval($_POST['department']);
$agentID = empty($_POST['agent']) ? NULL : intval($_POST['agent']);
$priority = $_POST['priority'];

$ticket = Ticket::getById($db, $ticketID);
$ticket->updateTicket($db, $departmentID, $agentID, $priority, $hashtagIDs);

http_response_code(200); // OK
echo json_encode(array(
    'success' => 'Ticket updated',
    'department' => $ticket->departmentName,
    'agent' => $ticket->assignedagent,
    'priority' => $ticket->priority,
    'hashtags' => array_map(fn($hashtag) => $hashtag->hashtagname, $ticket->hashtags),
    'status' => $ticket->status,
    'csrf' => $session->getCsrf()
));

?>
