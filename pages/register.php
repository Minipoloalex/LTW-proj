<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../templates/common.tpl.php');
require_once(__DIR__ . '/../templates/landing_page.tpl.php');
require_once(__DIR__ . '/../utils/session.php');
$session = new Session();
if ($session->isLoggedIn()) {
    exit(header('Location: main_page.php'));
}

drawLandingPageHeader();
drawRegisterForm($session);
output_footer(true);
?>
