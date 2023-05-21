<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../../utils/session.php');
require_once(__DIR__ . '/../../database/connection.db.php');
require_once(__DIR__ . '/../../database/client.class.php');

function handle_check_logged_in(Session $session) {
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        // csrf may be required
        echo_json_csrf($session, array('error' => 'User not logged in'));
        exit();
    }
}
function handle_check_csrf(Session $session, ?string $inputCSRF) {
    if (!$session->verifyCsrf($inputCSRF)) {
        http_response_code(403); // Forbidden
        echo_json_csrf($session, array('error' => 'CSRF token invalid'));
        exit();
    }
}
function echo_json_csrf(Session $session, array $message) {
    $message['csrf'] = $session->getCsrf();
    echo json_encode(
        $message,
    );
}

function handle_check_admin(Session $session, PDO $db) {
    if (!Client::isAdmin($db, $session->getId())) {
        http_response_code(403); // Forbidden
        echo_json_csrf($session, array('error' => 'You do not have permission for that.'));
        exit();
    }
}
?>
