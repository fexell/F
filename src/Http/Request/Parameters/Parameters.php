<?php

  namespace F\Http\Request;

  use F\Http\Request;
  use F\Http\Response;

  class Parameters extends Request {

    /** @var Response $response The response class */
    private Response $response;

    /** @var string|array|null $param The parameter to use */
    private string | array | null $param = null;

    /** @var array|null $result A variable to keep the resulting parameters (and their values) in */
    private string | array | null $result = [];

    /**
     * @param string|null $param The parameter to use
     */
    public function __construct(string | array | null $param = null) {
      if(!is_string($param) && !is_array($param) && !is_null($param))
        exit('The params parameter needs to be either a string, an array, or empty.');

      $this->response = new Response();
      $this->param = $param;
      $this->result = [];

      /**
       * If the parameter given is a string, then return the value from $_GET
       */
      if(is_string($this->param))
        $this->result = htmlspecialchars($_GET[ $this->param ]);

      /**
       * Else if the parameter given is an array, then loop through all the params provided,
       * and add/push it to the $this->result array
       */
      else if(is_array($this->param))
        foreach($this->param as $key => $value)
          if(array_key_exists($key, $_GET) && !empty($_GET[ $key ]))
            $this->result[ $key ] = htmlspecialchars($_GET[ $key ]);

          // If the array key does not exist or its value is empty,
          // exit with an error message
          else
            exit('Parameter key "' . $key . '" does not exist, or does not have a value.');

      /**
       * Otherwise, if $this->param isn't set (left empty), return it as null
       */
      else if(is_null($this->param) || empty($this->param))
        $this->result = null;
    }

    /**
     * Just a simple method to return the result (as an example, for being stored in a variable)
     */
    public function get(): mixed {
      return $this->result;
    }

    /**
     * Populate/push to $this->result with ALL $_GET parameters/data, and return $this for method chaining
     */
    public function all(): Parameters | string {

      // When getting ALL $_GET parameters, the params() method need to be left empty,
      // since we're not retrieving specific data; so check if $this->param IS NOT null
      if(!is_null($this->param))
        exit('When using the all() method for params, the given parameter/data needs to be left empty.');

      // For each element retrieved in (ALL) $_GET
      foreach($_GET as $key => $value)
        $this->result[ $key ] = htmlspecialchars($_GET[ $key ]);

      // Lastly return $this (this class) for method chaining
      return $this;
    }

    /**
     * Method for printing the result as json
     */
    public function print(): Parameters | string {

      // If the $result variable is an array, then print it as json
      if(is_array($this->result))
        return $this->response->json($this->result)->print();

      // Otherwise, if the $result variable is a string, return and convert the $result into an array,
      // to be printed as json
      else if(is_string($this->result))
        return $this->response->json([ $this->param => $this->result ])->print();

      return $this;
    }
  }