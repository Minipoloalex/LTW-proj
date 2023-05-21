<?php 

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../templates/hashtags.tpl.php');
require_once(__DIR__ . '/../database/hashtag.class.php');
require_once(__DIR__ . '/../database/client.class.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    die(header('Location: landing_page.php'));
}

$db = getDatabaseConnection();
$userType = Client::getType($db, $session->getId());
if ($userType !== 'Admin') {
    die(header('Location: main_page.php'));
}

output_header($session, $userType);
drawTitle("Manage Hashtags", "none");
drawHashtagsMenu(Hashtag::getHashtags($db));
output_footer();
?>