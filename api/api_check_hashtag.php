<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');


require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}
if (!is_valid_string($_POST['hashtag'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing hashtag parameter'));
    exit();
}
if (!is_valid_id($_POST['ticketID'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing ticketID parameter'));
    exit();
}
$db = getDatabaseConnection();
$hashtag = $_POST['hashtag'];
$ticketID = intval($_POST['ticketID']);
$userID = $session->getId();

if (!Client::canChangeTicketInfo($db, $userID, $ticketID)) {
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'User does not have access to ticket'));
    exit();
}
$hashtags = array_column(Hashtag::getByTicketId($db, $ticketID), 'hashtagname');


if (in_array($hashtag, $hashtags)) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Ticket already has hashtag'));
    exit();
}
$hastagExists = Hashtag::exists($db, $hashtag);
if (!$hastagExists) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Hashtag does not exist'));
    exit();
}

echo json_encode(array(
    'success' => 'Hashtag exists and ticket does not have it',
));
?>
