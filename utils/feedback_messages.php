<?php
declare(strict_types=1);
require_once(__DIR__ . '/session.php');

function echo_json_message(Session $session, array $message)
{
    $message['csrf'] = $session->getCsrf();
    echo json_encode(
        $message
    );
}
?>
