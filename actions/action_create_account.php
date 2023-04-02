<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/client.class.php');

$db = getDatabaseConnection();



header('Location: ../pages/main_page.php');
?>
