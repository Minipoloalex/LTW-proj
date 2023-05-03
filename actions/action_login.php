<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection.db.php');
  require_once(__DIR__ . '/../database/client.class.php');
  require_once(__DIR__ . '/../utils/validate.php');

  $db = getDatabaseConnection();

  if (!is_valid_email($_POST['email']) || !is_valid_string($_POST['password'])) {
    $session->addMessage('error', 'Invalid email or password!');
    die(header('Location: ../pages/landing_page.php'));
  }

  $client = Client::getClientWithPassword($db, $_POST['email'], $_POST['password']);
  
  if ($client) {
    $session->setId($client->id);
    $session->setName($client->username);
    $session->addMessage('success', 'Login successful!');
  } else {
    $session->addMessage('error', 'Wrong password!');
    die(header('Location: ../pages/landing_page.php'));
  }

  header('Location: ../pages/main_page.php');
  
?>
