<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/message.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_ONCE(__DIR__ . '/../database/image.class.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}
if (!$session->verifyCsrf($_POST['csrf'] ?? '')) {
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'CSRF token invalid'));
    exit();
}
$db = getDatabaseConnection();

if (!is_valid_string($_POST['message'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing message parameter. Message text is required'));
    exit();
}
if (!is_valid_ticket_id($db, $_POST['ticketID'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Invalid ticketID parameter'));
    exit();
}

$messageText = $_POST['message'];
$ticketID = intval($_POST['ticketID']);
$userID = $session->getId();

if (!Client::hasAcessToTicket($db, $userID, $ticketID)) {
    http_response_code(403); // Forbidden
    echo json_encode(array('error' => 'User does not have access to ticket'));
    exit();
}
$ticket = Ticket::getById($db, $ticketID);
if ($ticket->isClosed()) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'Ticket is closed'));
    exit();
}

if (isset($_FILES['image'])) {
    if (!is_dir('../images')) mkdir('../images');

    $tempFileName = $_FILES['image']['tmp_name'];

    $original = @imagecreatefromjpeg($tempFileName);
    if (!$original) $original = @imagecreatefrompng($tempFileName);
    if (!$original) $original = @imagecreatefromgif($tempFileName);
    if (!$original) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Unknown image format! Only recognize .jpeg .png and .gif'));
        exit();
    }

    $image = Image::addImage($db);
    $message = Message::addMessage($db, $userID, $ticketID, $messageText, $image->id);

    $fileNameID = "../images/$image->id.jpg";
    imagejpeg($original, $fileNameID);
}
else {
    $message = Message::addMessage($db, $userID, $ticketID, $messageText);
}

http_response_code(200); // OK
echo json_encode(array(
    'success' => 'Message successfully added',
    'id' => $message->id,
    'text' => $message->text,
    'userID' => $message->userID,
    'username' => $message->username,
    'date' => date('F j', $message->date),
    'csrf' => $session->getCsrf(),
));
?>
