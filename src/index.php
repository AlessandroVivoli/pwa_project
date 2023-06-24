<?php
include '../vendor/autoload.php';
include 'routing/routes.php';
include 'models/user.php';

function require_with($path, $vars)
{
    extract($vars);
    require $path;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

session_start();

$request = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;

if (!isset($_SESSION['user']) && isset($_COOKIE['user'])) {
    $_SESSION['user'] = $_COOKIE['user'];
}

$path = explode('?', $request)[0];
$path = preg_replace('/^\//', '', $path);

$viewDir = '/views/';
$errorsDir = '/errors/';
$actionsDir = '/actions/';

AppRoutes::init();

unset($_SERVER['status_message']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require 'utils/header_imports.php' ?>
    <title>
        <?php
        if (isset(AppRoutes::$routes[$path])) {
            echo AppRoutes::$routes[$path]->getName();
        } else {
            echo "404: Not Found";
        }
        ?>
    </title>
</head>

<body>
    <?php include './components/navbar.php' ?>
    <div id="wrapper">
        <?php

        $documentDir = __DIR__ . $viewDir;

        include __DIR__ . '/utils/unset_session_variables.php';

        switch ($path) {
            case AppRoutes::$home->getPath():
                require $documentDir . 'home.php';
                break;

            case AppRoutes::$music->getPath():
                require $documentDir . 'music.php';
                break;

            case AppRoutes::$sport->getPath():
                require $documentDir . 'sport.php';
                break;

            case AppRoutes::$administration->getPath():
                require $documentDir . 'administration/administration.php';
                break;

            case AppRoutes::$newBlogPost->getPath():
                require $documentDir . 'administration/child_views/new_blog_post.php';
                break;

            case AppRoutes::$blog->getPath():
                require $documentDir . 'blog.php';
                break;

            case AppRoutes::$login->getPath():
                require $documentDir . 'login.php';
                break;

            case AppRoutes::$register->getPath():
                require $documentDir . 'register.php';
                break;

            case AppRoutes::$logout->getPath():
                require $documentDir . 'logout.php';
                break;

            case AppRoutes::$deleteBlog->getPath():
                require __DIR__ . $actionsDir . 'deleteBlog.php';
                break;

            case AppRoutes::$addBlogPost->getPath():
                require __DIR__ . $actionsDir . 'addBlogPost.php';
                break;
            
            case AppRoutes::$edit->getPath():
                require $documentDir.'administration/child_views/new_blog_post.php';
                break;

            case AppRoutes::$save->getPath():
                require __DIR__.$actionsDir.'editBlogPost.php';
                break;

            default:
                $_SERVER['status_message'] = "The page you're trying to look for does not exist!";
                http_response_code(404);
                require __DIR__ . $errorsDir . '404.php';
                break;
        }
        ?>
    </div>
    <?php include './components/footer.php' ?>
</body>

</html>