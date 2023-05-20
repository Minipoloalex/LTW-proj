<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../utils/validate.php');

require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/handlers/api_common.php');
$session = new Session();
$db = getDatabaseConnection();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    handle_check_logged_in($session);
    if (!is_valid_string($_GET['hashtagName'])) {
        http_response_code(400); // Bad request
        echo json_encode(array('error' => 'Missing hashtag parameter'));
        exit();
    }
    $hashtag = Hashtag::getByName($db, $_GET['hashtagName']);
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
    exit();
}

http_response_code(405); // Method not allowed
echo json_encode(array('error' => 'Method not allowed'));
exit();

?>
