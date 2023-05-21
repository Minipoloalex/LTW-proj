<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../utils/session.php');
require_once(__DIR__ . '/../../utils/validate.php');
require_once(__DIR__ . '/../../database/connection.db.php');
require_once(__DIR__ . '/../../database/hashtag.class.php');
require_once(__DIR__ . '/../../database/department.class.php');
require_once(__DIR__ . '/../../database/client.class.php');
require_once(__DIR__ . '/../../database/ticket.class.php');

function handle_update_ticket(Session $session, PDO $db, ?string $dataHashtags, ?string $dataTicketID,
 ?string $dataDepartment, ?string $dataAgent, ?string $dataPriority) {
    $hashtags = $dataHashtags != NULL ? explode(',', $dataHashtags) : array();
    if (!is_valid_ticket_id($db, $dataTicketID)) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Invalid ticketID parameter'));
        exit();
    }
    $ticketID = intval($dataTicketID);

    if (!Client::canChangeTicketInfo($db, $session->getId(), $ticketID)) {
        http_response_code(403); // Forbidden
        echo_json_csrf($session, array('error' => 'User does not have access to ticket'));
        exit();
    }

    if (!is_valid_array_hashtag_ids($db, $hashtags)) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Hashtags are not valid'));
        exit();
    }
    $hashtagIDs = array_map('intval', $hashtags);

    if (!empty($dataDepartment) && !is_valid_department_id($db, $dataDepartment  ?? '')) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Invalid department parameter'));
        exit();
    }
    $departmentID = empty($dataDepartment) ? NULL : intval($dataDepartment);

    if (!empty($dataAgent) && !is_valid_agent_id($db, $dataAgent ?? '')) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Invalid agent parameter'));
        exit();
    }
    $agentID = empty($dataAgent) ? NULL : intval($dataAgent);

    if (!is_valid_priority($dataPriority)) {
        http_response_code(400); // Bad request
        echo_json_csrf($session, array('error' => 'Invalid priority parameter'));
        exit();
    }
    $priority = $dataPriority;

    if ($agentID != NULL) {
        $agent = Agent::getById($db, $agentID);
        if ($agent->departmentid !== NULL && $departmentID != NULL && $agent->departmentid !== $departmentID) {
            http_response_code(400); // Bad request
            echo_json_csrf($session, array('error' => 'Agent belongs to another department'));
            exit();
        }
    }

    $ticket = Ticket::getById($db, $ticketID);
    $action = $ticket->updateTicket($db, $departmentID, $agentID, $priority, $hashtagIDs, $session->getId());

    http_response_code(200); // OK
    echo_json_csrf($session, array(
        'success' => 'The ticket was successfully updated',
        'department' => $ticket->departmentName,
        'agent' => $ticket->assignedagent,
        'priority' => $ticket->priority,
        'hashtags' => array_map(fn($hashtag) => $hashtag->hashtagname, $ticket->hashtags),
        'status' => $ticket->status,
        'action_username' => $action->username,
        'action_text' => $action->text,
        'action_date' => date('F j', $action->date),
    ));
    exit();
}

?>
