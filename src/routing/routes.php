<?php
class Route
{
    private string $name;
    private string $path;

    function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    public function getName()
    {
        return $this->name;
    }
    public function getPath()
    {
        return $this->path;
    }
}

abstract class AppRoutes
{
    public static Route $home;
    public static Route $music;
    public static Route $sport;
    public static Route $administration;
    public static Route $blog;
    public static Route $login;
    public static Route $register;
    public static Route $newBlogPost;

    public static $routes;

    public static function init()
    {
        self::$home =  new Route('Home', '');
        self::$music =  new Route('Music', 'music');
        self::$sport =  new Route('Sport', 'sport');
        self::$administration =  new Route('Admin Panel', 'administration');
        self::$blog =  new Route('Blog', 'blog');
        self::$login =  new Route('Login', 'login');
        self::$register =  new Route('Register', 'register');
        self::$newBlogPost =  new Route('New Blog Post', 'administration/new');

        self::$routes = array(
            self::$home->getPath() => self::$home,
            self::$music->getPath() => self::$music,
            self::$sport->getPath() => self::$sport,
            self::$administration->getPath() => self::$administration,
            self::$blog->getPath() => self::$blog,
            self::$login->getPath() => self::$login,
            self::$register->getPath() => self::$register,
            self::$newBlogPost->getPath() => self::$newBlogPost,
        );
    }
}
