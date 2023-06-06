<?php

if (!isset($_SESSION['user'])) {
    $_SESSION['redirectTo'] = 'administration';

    header('Location: /login');
} else if (json_decode($_SESSION['user'])->level < 1) {
    http_response_code(403);
    $_SERVER['status_message'] = "You're not permitted to use administration tools.<br><em>If you think this is a mistake, contact your admin to try to resolve this problem.</em>";
    require 'errors/403.php';
}
