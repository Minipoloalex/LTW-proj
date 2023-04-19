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
    die(header('Location: ../pages/create_account.php'));
}

$account_exists = Client::check_acc_exists($db, $name, $username, $email);
if ($account_exists[0]) {
    $session->addMessage('error', $account_exists[1]);
    die(header('Location: ../pages/create_account.php'));
}

$user_id = Client::create_account($db, $name, $username, $email, $password, $confirm_password);
$session->setId($user_id);
$session->setName($username);
$session->addMessage('success', $account_exists[1]);


header('Location: ../pages/main_page.php');
?>

<?php
// TODO: put these functions in the right file
function check_valid_data(string $name, string $username, string $email, string $password, string $confirm_password) {
    // TODO: do not allow special characters in name/username. only letters, spaces and numbers: slide 24/63 web security
    if (!is_valid_string($name) || !is_valid_string($username) || !is_valid_string($email) || !is_valid_string($password) || !is_valid_string($confirm_password)) {
        return array(false, "Username, password, name and email are required");
    }
    if ($password != $confirm_password) {
        return array(false, "Passwords do not match");
    }
    if (strlen($password) < 6) {
        return array(false, "Password must have at least 6 characters");
    }
    return array(true, "");
}


?>
in