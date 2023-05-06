<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');

require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
$session = new Session();
if (!$session->isLoggedIn()) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}
if (!is_valid_string($_POST['hashtagName'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing hashtag parameter'));
    exit();
}

$db = getDatabaseConnection();
$hashtag = $_POST['hashtagName'];
$userID = $session->getId();

$hashtag = Hashtag::getByName($db, $hashtag);
if ($hashtag == NULL) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Hashtag does not exist'));
    exit();
}

http_response_code(200); // OK
echo json_encode(array(
    'success' => 'Hashtag exists',
    'hashtagID' => $hashtag->hashtagid,
));
?>
