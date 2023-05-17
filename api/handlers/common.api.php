<?php
declare(strict_types = 1);
function handle_check_logged_in(Session $session) {
    if (!$session->isLoggedIn()) {
        http_response_code(401); // Unauthorized
        echo json_encode(array('error' => 'User not logged in'));
        exit();
    }
}
function handle_check_csrf(Session $session, ?string $inputCSRF) {
    if (!$session->verifyCsrf($inputCSRF)) {
        http_response_code(403); // Forbidden
        echo json_encode(array('error' => 'CSRF token invalid'));
        exit();
    }
}
function echo_json_csrf(Session $session, array $message) {
    $message['csrf'] = $session->getCsrf();
    echo json_encode(
        $message,
    );
}
?>
