<?php
switch ($path) {
    case AppRoutes::$register->getPath():
        unset($_SESSION['registerSuccess']);
    case AppRoutes::$login->getPath():
        break;

    default:
        unset($_SESSION['redirectTo']);
        unset($_SESSION['registerSuccess']);
        break;
}

switch ($path) {
    case AppRoutes::$newBlogPost->getPath():
        break;

    default:
        unset($_SESSION['createBlogPostSuccess']);
        unset($_SESSION['createBlogPostError']);
        break;
}

switch ($path) {
    case AppRoutes::$administration->getPath():
        break;

    default:
        unset($_SESSION['deleteError']);
        unset($_SESSION['editBlogPostSuccess']);
        break;
}

switch($path) {
    case AppRoutes::$edit:
        break;
    
    default:
        unset($_SESSION['editBlogPostError']);
        break;
}
