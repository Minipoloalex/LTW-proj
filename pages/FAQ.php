<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/forum.class.php');
require_once(__DIR__ . '/../database/client.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/FAQ.tpl.php');
require_once(__DIR__ . '/../utils/session.php');

$session = new Session();
if (!$session->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$db = getDatabaseConnection();
$faqs = Forum::getFaqs($db, 10);
$type = Client::getType($db, $session->getId());

output_header();
output_faq_form();
output_all_faqs($faqs, $type);
output_footer();

?>
