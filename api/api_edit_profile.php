<?php
/** 
 * Edit their profile (at least name, username, password, and e-mail). 
 * Register a new account.
 * Login and Logout. */

declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if (!$session->isLoggedIn()){
  http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../utils/validate.php');

if (!is_valid_string($_POST['csrf'])){
  http_response_code(400); // Bad request
  echo json_encode(array('error' => 'Missing csrf parameter'));
  exit();
}

if (!$session->verifyCsrf($_POST['csrf'])){
  http_response_code(403); // Forbidden
  echo json_encode(array('error' => 'Invalid csrf token'));
  exit();
}


$db = getDatabaseConnection();

$client = Client::getById($db, $session->getId());

if ($client) {
  $client->name = $_POST['name'];
  
  $client->save($db);

  $session->setName($client->name());
}

header('Location: ../pages/profile.php');

?>
