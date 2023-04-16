<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/landing_page.tpl.php');


drawShape();
/*body*/
drawLandingPageHeader();
drawLoginForm();
drawLogo();
/*body end*/
output_footer();
?>
