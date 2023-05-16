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

if (!is_valid_ticket_id($db, $_POST['ticketID'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Invalid ticketID parameter'));
    exit();
}
$ticketID = intval($_POST['ticketID']);

if (!Client::canChangeTicketInfo($db, $session->getId(), $ticketID)) {
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'User does not have access to ticket'));
    exit();
}

if (!is_valid_array_hashtag_ids($db, $hashtags)) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Hashtags are not valid'));
    exit();
}
$hashtagIDs = array_map('intval', $hashtags);

if (!empty($_POST['department']) && !is_valid_department_id($db, $_POST['department']  ?? '')) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Invalid department parameter'));
    exit();
}
$departmentID = empty($_POST['department']) ? NULL : intval($_POST['department']);

if (!empty($_POST['agent']) && !is_valid_agent_id($db, $_POST['agent'] ?? '')) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Invalid agent parameter'));
    exit();
}
$agentID = empty($_POST['agent']) ? NULL : intval($_POST['agent']);

if (!is_valid_priority($_POST['priority'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing priority parameter'));
    exit();
}
$priority = $_POST['priority'];

if ($agentID != NULL) {
    $agent = Agent::getById($db, $agentID);
    if ($agent->departmentid !== NULL && $agent->departmentid !== $departmentID) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Agent belongs to another department'));
        exit();
    }
}

$ticket = Ticket::getById($db, $ticketID);
$action = $ticket->updateTicket($db, $departmentID, $agentID, $priority, $hashtagIDs, $session->getId());

http_response_code(200); // OK
echo json_encode(array(
    'success' => 'The ticket was successfully updated',
    'department' => $ticket->departmentName,
    'agent' => $ticket->assignedagent,
    'priority' => $ticket->priority,
    'hashtags' => array_map(fn($hashtag) => $hashtag->hashtagname, $ticket->hashtags),
    'status' => $ticket->status,
    'action_username' => $action->username,
    'action_text' => $action->type,
    'action_date' => date('F j', $action->date),
    'csrf' => $session->getCsrf()
));

?>
