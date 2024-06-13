<?php

  namespace F;

  require_once __DIR__ . '/../Http/Request/Request.php';
  require_once __DIR__ . '/../Http/Request/Parameters/Parameters.php';
  require_once __DIR__ . '/../Http/Response/Response.php';

  use F\Http\Request;
  use F\Http\Parameters;
  use F\Http\Response;

  /**
   * The Router class
   * 
   * This class handles everything regarding router, routes, etc...
   * 
   * @access public
   */
  class Router {

    /**
     * @var Request $request The variable to "hold" he Request class
     * @access protected
     */
    protected Request $request;

    /**
     * @var Response $response The variable to "hold" the Response class
     * @access protected
     */
    protected Response $response;

    /**
     * @var string $route The route/path to "listen" to, and set by the router method ($this->router('/{ $route }'))
     * @access protected
     */
    protected string $route;

    /** 
     * @var array $routes Array to store all the routes in (with their corresponding request method, route and callback)
     * @access private
     */
    private static array $routes = [];

    /**
     * The Router's constructor
     * @param string $route The route/path to "listen" to
     * @access public
     */
    public function __construct(string $route) {

      /** @var Request $this->request Points to a new Request class */
      $this->request = new Request();

      /** @var Response $this->response Points to a new Response class */
      $this->response = new Response();

      /** @var string $this->route The current route ($app->router( ... )) */
      $this->route = $route;
    }

    /**
     * Method to just return the request method
     * @access private
     * @return string Returns the request method
     */
    private function getRequestMethod(): string {
      return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Just a method to check if it's the correct request method
     * @param string $method The request method to compare to (GET|POST|PUT|...)
     * @access private
     * @return boolean Returns a boolean if the request method equals to the set $method
     */
    private function isRequestMethod(string $method): bool {
      return $this->getRequestMethod() === $method;
    }

    /**
     * Just a method to return the request uri
     * @access private
     * @return string Returns the request uri/path
     */
    private function getRequestUri(): string {
      return $_SERVER['REQUEST_URI'];
    }

    /**
     * Just a method to check if it's the correct route
     * @param string $route The requested route to compare to (example: '/', '/hello', '/user', etc...)
     * @access private
     * @return boolean Returns a boolean if the request uri equals to the $route
     */
    private function isRequestUri(string $route): bool {
      return $this->getRequestUri() === $route;
    }

    /**
     * Function for the $this->runRoutes() method, in case the route/path could not be found
     * @access private
     * @return mixed Returns/exits with an error message
     */
    private function routeNotRegistered(): mixed {

      // If the request method ($_SERVER['REQUEST_URI']) IS NOT in the $routes array,
      // exit with a 404 error
      if(!in_array($this->getRequestUri(), self::$routes)) {
        http_response_code(404);
        exit('404 - Route "' . $this->getRequestUri() . '" is not registered as a route');
      }

      // Return null since "all code paths needs to have a return value"
      return null;
    }

    /**
     * Function to check if there are any routes at all
     * @access private
     * @return void Returns/exits with an error message, that no routes were found
     */
    private function isRoutes(): void {
      if(count(self::$routes) === 0) {
        http_response_code(404);
        exit('404 - No routes found');
      }
    }

    /**
     * The method to run the callback-closure in the request method
     * @param callable $callback The callback-closure in the request method ($app->router('/{ URI }')->get(function() { ... }))
     * @return array Returns an array map, running the callback-closure and binding response and request to the callback-closure parameters
     */
    private function callback(callable $callback): mixed {
      return call_user_func_array($callback, [ $this->request, $this->response ]);
    }

    /**
     * A method to add the route to the $this->routes array (in other words: to "register" the route)
     * @param string $method The request method (GET|POST|PUT|...)
     * @param string $route The route to "listen" to
     * @param callable $callback The callback-closure function to run on the route
     * @access private
     * @return array Adds a route to $this->routes array (with the type of method and callback method)
     */
    private function addRoute(string $method, string $route, callable $callback): array {

      // If $route starts WITHOUT a forward-slash, prepend a forward slash to the route
      if(!str_starts_with($route, '/')) $route = '/' . $route;

      // Else if the callback IS NOT a function, return/exit with an error (and error message)
      else if(!is_callable($callback)) exit('The callback method needs to be a function.');

      return self::$routes[ $route ] = [
        'method' => $method,
        'route' => $route,
        'callback' => $callback
      ];
    }

    /**
     * Method to go through all routes in $this->routes and run
     * the callback method to the corresponding route
     * @return mixed Either return the $callback-closure if route exists, otherwise exit with an error
     * @access private
     */
    private function runRoutes(): mixed {
      foreach(self::$routes as $route => $item) {
        
        // If the request method equals the one from the request method ($app->router('/{ URI }')->get(function() { ... })),
        // and if the request uri equals the one set as a route,
        // and if the uri DOES NOT contain the parameter prefix (:),
        // run the callback-closure on the given route
        if($this->isRequestMethod($item[ 'method' ])
        && $this->isRequestUri($route)
        && !preg_match('/(\:)/', $route)) {
          return $this->callback($item[ 'callback' ]);
        }

        // Else if the request method equals the one from the request method,
        // and the uri contains a parameter prefix (:),
        // handle a route with parameters
        else if($this->isRequestMethod($item[ 'method' ])
        && preg_match_all('/((\:)([a-z0-9]+))/i', $route, $matches)) {

          /**
           * @var array $explodedSubdirectories Extract all subdirectories from the url/uri in $routes
           */
          $explodedRoute = explode('/', $route);

          /**
           * @var array $explodedUri Extract all subdirectories from $this->getRequestUri() ($_SERVER['REQUEST_URI])
           */
          $explodedUri = explode('/', $this->getRequestUri());

          /** 
           * @var array $extractedParameters Extract all subdirectories starting with a colon (url parameters) from the $route
           */
          $extractedParameters = preg_grep('/(\:)([a-z0-9]+)/i', $explodedRoute);

          // Checks if the count of extracted parameters ($extractedParameters) matches
          // the count of matches found in the original route pattern ($matches).
          // If the counts don't match, the code returns a "route not registered" error,
          // indicating that the route pattern is invalid
          if(count($extractedParameters) !== count($matches[ 0 ]))
            return $this->routeNotRegistered();

          // For each extracted parameter, replace each ":parameter" with the value of that parameter
          foreach($extractedParameters as $key => $keyName) {

            // If the array key (int: 0, 1, 2, ...) exists in the $explodedUri, then change the value for
            // the corresponding $explodedRoute index to the one in the $explodedUri
            // ! This is in case a match was found in the preg_match for this else if block,
            // ! but it found less parameters than given in the route; otherwise an error will be thrown
            if(array_key_exists($key, $explodedUri)) {
              $explodedRoute[ $key ] = $explodedUri[ $key ];

              // Set each key name as a $_GET parameter, which equals the value of each exploded route
              $_GET[ preg_replace('/(\:)/', '', $keyName) ] = $explodedRoute[ $key ];

              // Push each key name into the Request's $params variable
              $this->request::$params[ preg_replace('/(\:)/', '', $keyName) ] = $explodedRoute[ $key ];
            }
          }

          /**
           * @var string $uri Combine all the array values in $explodedParameters into one string/uri-path
           */
          $uri = implode('/', $explodedRoute);

          /**
           * If the requested uri matches the $uri return/run the callback-closure
           * @see $uri The imploded $uri
           */
          if($this->isRequestUri($uri))
            return $this->callback($item[ 'callback' ]);
        }
      }

      // If the route that is trying to be accessed is not registered, return a 404 error
      return $this->routeNotRegistered();
    }

    /**
     * The router method for get calls ($app->router('/{ PATH }')->get(...))
     * @param callable $callback Run the anonymous function when running the get method from Router class
     * @access public
     */
    public function get(callable $callback): array {
      return $this->addRoute('GET', $this->route, $callback);
    }

    /**
     * The router method for post calls ($app->router('/{ PATH }')->post(...))
     * @param callable $callback Run the anonymous function when running the post method from Router class
     * @access public
     */
    public function post(callable $callback): array {
      return $this->addRoute('POST', $this->route, $callback);
    }

    /**
     * First, run a check to see if there are any routes at all, and
     * secondly, run all the routes, and their callback methods
     * @access protected
     */
    protected function dispatch(): void {

      /** @see isRoutes() */
      $this->isRoutes();

      /** @see runRoutes() */
      $this->runRoutes();
    }
  }