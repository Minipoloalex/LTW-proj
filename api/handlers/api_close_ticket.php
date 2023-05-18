<?php
declare(strict_types=1);
require_once(__DIR__ . '/../../utils/session.php');
require_once(__DIR__ . '/../../utils/validate.php');
require_once(__DIR__ . '/../../database/ticket.class.php');
require_once(__DIR__ . '/../../database/connection.db.php');
function handle_api_close_ticket(Session $session, PDO $db, ?string $dataTicketID, ?string $dataStatus) {
    if (!is_valid_ticket_id($db, $dataTicketID)) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Invalid ticketID parameter'));
        exit();
    }
    if (!is_valid_string($dataStatus) || !is_valid_status($dataStatus)) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid status parameter'));
        exit();
    }
    $ticketID = intval($dataTicketID);
    $status = $dataStatus;
    $userID = $session->getId();

    if (!Client::hasAcessToTicket($db, $userID, $ticketID)) {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'You do not have access to the ticket'));
        exit();
    }

    if ($status !== 'closed') {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Invalid status parameter'));
        exit();
    }

    $action = Ticket::closeTicket($db, $ticketID, $userID);

    $ticket = Ticket::getById($db, $ticketID);
    if (!$ticket) {
        http_response_code(500); // Internal server error
        echo_json_csrf($session, array('error' => 'Ticket does not exist'));
        exit();
    }

    http_response_code(200); // OK
    echo_json_csrf($session, array(
        'success' => 'The ticket was successfully closed',
        'ticketID' => $ticket->ticketid,
        'department' => $ticket->departmentName,
        'status' => $ticket->status,
        'agent' => $ticket->assignedagent,
        'priority' => $ticket->priority,
        'hashtags' => array_column($ticket->hashtags, 'hashtagname'),
        'action_username' => $action->username,
        'action_text' => $action->type,
        'action_date' => date('F j', $action->date)
    ));
}
?>
