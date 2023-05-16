<?php

$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: ../pages/main_page.php'));

require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../database/ticket.class.php');
require_once(__DIR__ . '/../utils/session.php');


$db = getDatabaseConnection();
$userID = $session->getId();
$type = Client::getType($db, $userID);

// Complete this
// $followingTickets = Client::getFollowing($db, $userID);

?>
