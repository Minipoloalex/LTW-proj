<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');  // TODO: check if csrf here is needed
  $session = new Session();
  if (!$session->isLoggedIn()) {
    exit(header('Location: ../pages/landing_page.php'));
  }
  $session->logout();

  header('Location: ../pages/landing_page.php');
?>
