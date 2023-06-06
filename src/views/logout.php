<?php
session_unset();
setcookie('user', "", time() - 3600);
header("Location: /");
