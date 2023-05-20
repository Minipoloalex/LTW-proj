<?php
declare(strict_types=1);
require_once(__DIR__ . '/../../database/connection.db.php');
require_once(__DIR__ . '/../../database/client.class.php');
require_once(__DIR__ . '/../../utils/validate.php');
require_once(__DIR__ . '/../../utils/session.php');
require_once(__DIR__ . '/api_common.php');

function handle_edit_profile(Session $session, PDO $db, ?string $dataName, ?string $dataUsername, ?string $dataEmail,
?string $dataEditPass, ?string $dataOldPass, ?string $dataNewPass) {
  if (!is_valid_name($dataName)) {
    http_response_code(400); // Bad request
    echo_json_csrf($session, array('error' => 'Not a valid name.'));
    exit();
  }
  if (!is_valid_username($dataUsername)) {
    http_response_code(400); // Bad request
    echo_json_csrf($session, array('error' => 'Not a valid username.'));
    exit();
  }
  if (!is_valid_email($dataEmail)) {
    http_response_code(400); // Bad request
    echo_json_csrf($session, array('error' => 'Not a valid email.'));
    exit();
  }
  if (!is_valid_string($dataOldPass)) {
    http_response_code(400); // Bad request
    echo_json_csrf($session, array('error' => 'Current password is required to edit information.'));
    exit();
  }
  
  $client = Client::getById($db, $session->getId());
  if (!$client) {
    http_response_code(401); // Unauthorized
    echo_json_csrf($session, array('error' => 'Your account does not exist.'));
    exit();
  }
  
  if (!$client->isEmailEqual($db, $dataEmail)) {
    $email_client = Client::getByEmail($db, $dataEmail);
    if ($email_client && $email_client->id != $session->getId()) { /* if the email already exists for another user */
      http_response_code(401); // Unauthorized
      echo_json_csrf($session, array('error' => 'Email already in use.'));
      exit();
    }
  }
  
  if (!$client->isUsernameEqual($db, $dataUsername)) {
    $username_client = Client::getByUsername($db, $dataUsername);
    if ($username_client && $username_client->id != $session->getId()) {
      http_response_code(400); // Bad request
      echo_json_csrf($session, array('error' => 'Username already in use.'));
      exit();
    }
  }
  
  if (!$client->isPassEqual($db, $dataOldPass)) {
    http_response_code(400); // Bad request
    echo_json_csrf($session, array('error' => 'Current password is incorrect.'));
    exit();
  }
  
  if ($dataEditPass === 'yes') {
    if (!is_valid_string($dataNewPass)) {
      http_response_code(400); // Bad request
      echo_json_csrf($session, array('error' => 'New password must not be empty.'));
      exit();
    }
    
    if ($client->isPassEqual($db, $dataNewPass)) {
      http_response_code(400); // Bad request
      echo_json_csrf($session, array('error' => 'New password is the same as the old one.'));
      exit();
    }

    if (!is_valid_password($dataNewPass)) {
      http_response_code(400); // Bad request
      echo_json_csrf($session, array('error' => "Passwords must have at least 8 characters, 1 uppercase letter, 1 lowercase letter, 1 number and 1 special character"));
      exit();
    }
    $client->updatePass($db, $dataNewPass);
  }
  $client->updateUserInfo($db, $dataName, $dataUsername, $dataEmail);
  
  http_response_code(200); // OK
  echo_json_csrf($session, array(
      'success' => 'Profile successfully updated.',
    ));
}
?>
