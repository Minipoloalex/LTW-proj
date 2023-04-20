<?php
/** 
 * Edit their profile (at least name, username, password, and e-mail). 
 * Register a new account.
 * Login and Logout. */

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()) die(header('Location: ../pages/landing_page.php'));

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');

require_once(__DIR__ . '/../utils/validate.php');

if (!is_valid_string($_POST['csrf'])) die(header('Location: ../pages/main_page.php'));
if (!$session->verifyCsrf($_POST['csrf'])) die(header('Location: ../pages/main_page.php'));


$db = getDatabaseConnection();

$client = Client::getById($db, $session->getId());

if ($client) {
  $client->name = $_POST['name'];
  
  $client->save($db);

  $session->setName($client->name());
}

header('Location: ../pages/profile.php');

?>
