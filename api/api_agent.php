<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../database/department.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/handlers/api_common.php');

$session = new Session();
handle_check_logged_in($session);
$db = getDatabaseConnection();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['departmentID'])) {
        handleAgentFilterByDepartment($session, $db, $_GET['departmentID']);
    }
}

http_response_code(405); // Method Not Allowed
echo json_encode(array(
    'error' => 'Invalid request method'
));


function handleAgentFilterByDepartment(Session $session, PDO $db, string $departmentID) {
    if (Client::getType($db, $session->getId()) === 'Client') {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'You are a Client. You do not have access to this type of information'));
        exit();
    }
    if (empty($departmentID)) {
        $agents = Agent::getAgents($db);
    }
    else {
        if (!is_valid_department_id($db, $departmentID)) {
            http_response_code(400); // Bad Request
            echo json_encode(array('error' => 'Invalid department'));
            exit();
        }
        $agents = Agent::getByDepartment($db, intval($departmentID));
        foreach ($agents as $agent)  {
            error_log($agent->name);
        }
    }

    echo json_encode(array(
        'success' => 'Department was changed and agents were filtered by that department.',
        'agents' => array_map(function ($agent) {
            return array('id' => $agent->id,
                'username' => $agent->username);
        }, $agents)
    ));
    exit();
}

?>
