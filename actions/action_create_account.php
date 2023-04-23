<?php
declare(strict_types = 1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if ($session->isLoggedIn()) {
    die(header('Location: ../pages/main_page.php'));
}

require_once(__DIR__ . '/../database/connection.db.php');
$db = getDatabaseConnection();

require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/client.class.php');

$name = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

$valid_data = check_valid_data($name, $username, $email, $password, $confirm_password);
if (!$valid_data[0]) {
    $session->addMessage('error', $valid_data[1]);
    die(header('Location: ../pages/register.php'));
}

$account_exists = Client::check_acc_exists($db, $name, $username, $email);
if ($account_exists[0]) {
    $session->addMessage('error', $account_exists[1]);
    die(header('Location: ../pages/register.php'));
}

$user_id = Client::create_account($db, $name, $username, $email, $password, $confirm_password);
$session->setId($user_id);
$session->setName($username);
$session->addMessage('success', $account_exists[1]);


header('Location: ../pages/main_page.php');
?>

