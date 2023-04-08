<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/forum.class.php');

require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/FAQ.tpl.php');

$db = getDatabaseConnection();
$faqs = Forum::getFaqs($db, 10);

output_header();
output_all_faqs($faqs);
output_footer();

?>
