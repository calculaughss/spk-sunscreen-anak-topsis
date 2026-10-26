<?php
require_once '../includes/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION['admin_id'], $_SESSION['admin_nama']);
header('Location: login.php');
exit;
