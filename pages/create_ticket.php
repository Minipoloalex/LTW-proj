<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../templates/create_ticket.tpl.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

output_header();
output_create_ticket_form();
output_footer();

?>
