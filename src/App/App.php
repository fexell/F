<?php

  namespace F;

  require_once __DIR__ . '/../Router/Router.php';

  use F\Router;

  /**
   * The App class - The main class
   * 
   * @access public
   */
  class App extends Router {

    /** @var Router $router The router variable to hold the new Router class */
    public Router $router;

    public function __construct() {

    }

    /**
     * The router method to be chained from $app ($app->router('/{ ROUTE }'))
     * @param string $route The route/path to "listen" to
     */
    public function router(string $route) {
      return $this->router = new Router($route);
    }

    /**
     * The run function, to run the dispatch method in the Router class.
     * This makes it only possible to run routes BEFORE $app->run(); otherwise the routes won't run (or be registered).
     * It also makes it possible to check for NO routes and return a 404 (not found) error.
     */
    public function run() {
      return $this->router->dispatch();
    }
  }