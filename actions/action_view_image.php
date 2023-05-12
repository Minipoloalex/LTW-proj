<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/image.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/message.class.php');

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/landing_page.php'));
}
// CSRF ?
$db = getDatabaseConnection();
if (!is_valid_id($_GET['messageID'])) {
    die(header('Location: ../pages/main_page.php'));
}
$messageID = intval($_GET['messageID']);
$message = Message::getById($db, $messageID);
if ($message == null) {
    die(header('Location: ../pages/main_page.php'));
}
$ticketID = $message->ticketID;
if (!Client::hasAcessToTicket($db, $session->getId(), $ticketID)) {
    die(header('Location: ../pages/main_page.php'));
}
if ($message->imageID == null) {
    die();
}
$image = Image::getImage($db, $message->imageID);
if ($image == null) {
    die();
}

$image_path = '../images/' . $image->id . '.jpg';
if (!file_exists($image_path)) {
    die();
}

header('Content-Type: image/jpeg');
header('Content-Length: ' . filesize($image_path));
readfile($image_path);
?>
