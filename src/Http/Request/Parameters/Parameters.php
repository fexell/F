<?php

  namespace F\Http\Request;

  use F\Http\Request;
  use F\Http\Response;
  use F\Http\Response\Json;
  use F\Http\Response\Error;

  class Parameters extends Request {

    /** @var Response $response The response class */
    private Response $response;

    /** @var string|array|null $param The parameter to use */
    private string | array | null $param = null;

    /** @var array|null $result A variable to keep the resulting parameters (and their values) in */
    private array | null $result = [];

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
       * If the parameter given is a string, then add/push it as an array element
       * to $this->result
       */
      if(is_string($this->param))
        $this->result[ $this->param ] = htmlspecialchars($_GET[ $this->param ]);

      /**
       * Else if the parameter given is an array, then loop through all the params
       * and add/push it to the $this->result array
       */
      else if(is_array($this->param))
        foreach($this->param as $key => $value)
          if(array_key_exists($key, $_GET) && !empty($_GET[ $key ]))
            $this->result[ $key ] = htmlspecialchars($_GET[ $key ]);

          else
            return $this->response->error('Parameter key "' . $key . '" does not exist, or does not have a value.')->toPlainText()->print();

      /**
       * Otherwise, if $this->param isn't set (left empty), return it as null
       */
      else
        $this->result = null;
    }

    /**
     * Just a simple method to return the result (to be stored in a variable, as an example)
     */
    public function get() {
      return $this->result;
    }

    /**
     * Populate/push to $this->result with ALL $_GET parameters/data, and return $this for method chaining
     */
    public function all() {
      if(!is_null($this->param))
        return $this->response->error('When using the all method for params, the given parameter/data needs to be left empty.');

      foreach($_GET as $key => $value)
        $this->result[ $key ] = htmlspecialchars($_GET[ $key ]);

      return $this;
    }

    /**
     * Method for printing the result as json
     */
    public function print() {
      return $this->response->json($this->result)->print();
    }
  }