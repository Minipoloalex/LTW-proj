<?php

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

if (!is_valid_string($_POST['answer'])) {
    http_response_code(400); // Bad request
    echo json_encode(array('error' => 'Missing answer parameter'));
    exit();
}

$db = getDatabaseConnection();
$question = $_POST['question'];
$answer = $_POST['answer'];
$faq = Forum::getFaq($db, $question, $answer);


if (!$faq) {
    http_response_code(500); // Internal server error
    echo json_encode(array('error' => 'Failed to find FAQ on database'));
    exit();
}

else {
    $faq = Forum::updateFaq($db, $question, $answer);
}

echo json_encode(array(
    'id' => $faq->forumId,
    'question' => $faq->question,
    'answer' => $faq->answer,
));

http_response_code(200); // OK
echo json_encode(array('success' => 'FAQ updated'));

?>
