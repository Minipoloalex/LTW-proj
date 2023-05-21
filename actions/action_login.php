<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../utils/validate.php');
$session = new Session();
if ($session->isLoggedIn()) {
  exit(header('Location: ../pages/main_page.php'));
}
$db = getDatabaseConnection();

if (!is_valid_email($_POST['email'])) {
  $session->addMessage('error', 'Invalid email!');
  die(header('Location: ../pages/landing_page.php'));
}
if (!is_valid_string($_POST['password'])) {
  $session->addMessage('error', 'Empty password! Please fill in your password.');
  die(header('Location: ../pages/landing_page.php'));
}

$client = Client::getClientWithPassword($db, $_POST['email'], $_POST['password']);

if ($client) {
  $session->setId($client->id);
  $session->setUsername($client->username);
  $session->addMessage('success', 'Login successful!');
} else {
  $session->addMessage('error', 'Wrong password!');
  die(header('Location: ../pages/landing_page.php'));
}

$type = Client::getType($db, $client->id);

if ($type === 'Client')
  header('Location: ../pages/my_tickets.php');
else if ($type === 'Admin')
  header('Location: ../pages/main_page.php');
else
  header('Location: ../pages/assigned_tickets.php');
?>
