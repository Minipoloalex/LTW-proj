<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/image.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/agent.class.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: ../pages/landing_page.php'));
}
// CSRF ?
$db = getDatabaseConnection();
if (!is_valid_ticket_id($db, $_GET['ticketID'])) {
    die(header('Location: ../pages/main_page.php'));
}
$ticketID = intval($_GET['ticketID']);
$ticket = Ticket::getByID($db, $ticketID);
if (!Client::hasAcessToTicket($db, $session->getId(), $ticketID)) {
    die(header('Location: ../pages/main_page.php'));
}

if (!is_valid_id($_GET['imageID'])) {
    die(header('Location: ../pages/main_page.php'));
}
$imageID = intval($_GET['imageID']);

$image = Image::getImageInfo($db, $imageID, $ticketID);
if (!$image) {
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
