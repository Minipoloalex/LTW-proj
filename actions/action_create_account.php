<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/client.class.php');

$session = new Session();
if ($session->isLoggedIn()) {
    die(header('Location: ../pages/main_page.php'));
}
$db = getDatabaseConnection();

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

$account_exists = check_acc_exists($db, $name, $username, $email, $password, $confirm_password);
if ($account_exists[0]) {
    $session->addMessage('error', $account_exists[1]);
    die(header('Location: ../pages/create_account.php'));
}

$user_id = create_account($db, $name, $username, $email, $password, $confirm_password);
$session->setId($user_id);
// $session->setName($name);
$session->addMessage('success', $account_exists[1]);


header('Location: ../pages/main_page.php');
?>

<?php
// TODO: put these functions in the right file
function check_valid_data(string $name, string $username, string $email, string $password, string $confirm_password) {
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
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

function check_acc_exists(PDO $db, string $name, string $username, string $email, string $password, string $confirm_password) {
    $stmt = $db->prepare('SELECT * FROM CLIENT WHERE Username = ?');
    $stmt->execute(array($username));
    if ($stmt->fetch()) {
        return array(true, "Username already exists");
    }
    $stmt = $db->prepare('SELECT * FROM CLIENT WHERE Email = ?');
    $stmt->execute(array($email));
    if ($stmt->fetch()) {
        return array(true, "Email already exists");
    }
    return array(false, "Account registered");
}

function create_account(PDO $db, string $name, string $username, string $email, string $password, string $confirm_password) : int {
    $stmt = $db->prepare('INSERT INTO CLIENT (Name, Username, Email, Password) VALUES (?, ?, ?, ?)');
    $stmt->execute(array($name, $username, $email, $password));
    return intval($db->lastInsertId());
}

?>
