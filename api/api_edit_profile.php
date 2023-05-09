<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../utils/session.php');

$session = new Session();
if (!$session->isLoggedIn()) {
  http_response_code(401); // Unauthorized
  echo json_encode(array('error' => 'You are not logged in.'));
  exit();
}
if (!$session->verifyCsrf($_POST['csrf'] ?? '')) {
  http_response_code(403); // Forbidden
  echo json_encode(array('error' => 'Invalid csrf token. Please reload the page.'));
  exit();
}

if (!is_valid_name($_POST['name'])) {
  http_response_code(400); // Bad request
  echo json_encode(array('error' => 'Not a valid name.'));
  exit();
}

if (!is_valid_username($_POST['username'])) {
  http_response_code(400); // Bad request
  echo json_encode(array('error' => 'Not a valid username.'));
  exit();
}

if (!is_valid_email($_POST['email'])) {
  http_response_code(400); // Bad request
  echo json_encode(array('error' => 'Not a valid email.'));
  exit();
}

if (!is_valid_string($_POST['oldpass'])) {
  http_response_code(400); // Bad request
  echo json_encode(array('error' => 'Your old password is incorrect.'));
  exit();
}


$db = getDatabaseConnection();

$client = Client::getById($db, $session->getId());
if (!$client) {
  http_response_code(401); // Unauthorized
  echo json_encode(array('error' => 'User not logged in.'));
  exit();
}

/*error handling -> first check if the user typed a different email/username than before, and then if the new ones are already used */
if (!$client->isEmailEqual($db, $_POST['email'])) {
  $email_client = Client::getByEmail($db, $_POST['email']);
  if ($email_client && $email_client->id != $session->getId()) { /* if the email already exists for another user */
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'Email already in use.'));
    exit();
  }
}

if (!$client->isUsernameEqual($db, $_POST['username'])) {
  $username_client = Client::getByUsername($db, $_POST['username']);
  if ($username_client && $username_client->id != $session->getId()) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Username already in use.'));
    exit();
  }
}

if (!$client->isPassEqual($db, $_POST['oldpass'])) {
  http_response_code(400); // Bad request
  echo json_encode(array('error' => 'Old password is incorrect.'));
  exit();
}

if ($_POST['editpass'] === 'yes') { /* if the edit password button was pushed, and is now with the content 'cancel' */
  if (!is_valid_string($_POST['newpass'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Not a valid new password.'));
    exit();
  }
  
  if ($client->isPassEqual($db, $_POST['newpass'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'New password is the same as the old one.'));
    exit();
  }

  if (!check_valid_password($_POST['newpass'])[0]) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Password must be at least 6 characters long.'));
    exit();
  }
  $client->updatePass($db, $_POST['newpass']);
}
$client->updateUserInfo($db, $_POST['name'], $_POST['username'], $_POST['email']);

http_response_code(200); // OK
echo json_encode(
  array(
    'success' => 'Profile successfully updated.',
    'csrf' => $session->getCsrf()
  ));

?>
