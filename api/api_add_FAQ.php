<?php
declare(strict_types=1);
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/forum.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../utils/validate.php');
require_once(__DIR__ . '/../database/connection.db.php');

$session = new Session();

if (!$session->isLoggedIn()) {
    http_response_code(401); // Unauthorized
    echo json_encode(array('error' => 'User not logged in'));
    exit();
}

if (!is_valid_string($_POST['question'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing question parameter'));
    exit();
}


$db = getDatabaseConnection();
$question = $_POST['question'];
$faq = Forum::addFaq($db, $question);

if (!$faq) {
    http_response_code(500); // Internal server error
    echo json_encode(array('error' => 'Failed to add FAQ to database'));
    exit();
}

echo json_encode(array(
    'id' => $faq->forumId,
    'question' => $faq->question,
    'answer' => $faq->answer,
));
?>
