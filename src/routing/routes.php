<?php
class Route
{

    /**
     * @var array
     */
    private static $routes = array();

    private string $name;
    private string $path;

    function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;

        array_push(self::$routes, $this);
    }

    public function getName()
    {
        return $this->name;
    }
    public function getPath()
    {
        return $this->path;
    }

    public static function getRoutes()
    {
        return self::$routes;
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
    public static Route $logout;
    public static Route $deleteBlog;
    public static Route $addBlogPost;
    public static Route $edit;
    public static Route $save;

    public static $routes = array();

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
        self::$logout = new Route('Logout', 'logout');
        self::$deleteBlog = new Route('Deleting blog post from the database...', 'deleteBlog');
        self::$addBlogPost = new Route('Adding blog post to the database...', 'addBlogPost');
        self::$edit = new Route('Edit Blog Post', 'edit');
        self::$save = new Route('Saving changes to the database...', 'save');

        $routesArray = Route::getRoutes();

        foreach ($routesArray as $route) {
            self::$routes[$route->getPath()] = $route;
        }
    }
}
