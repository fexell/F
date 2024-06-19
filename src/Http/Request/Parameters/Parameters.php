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
        exit('The params parameter needs to be either a string, an array, or left empty.');

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
          if(array_key_exists($key, $_GET) && !empty($value))
            $this->result[ $key ] = htmlspecialchars($value);

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

    public function print(): string {
      if(is_string($this->result))
        return $this->response->json([ $this->param => $this->result ])->print();

      else if(is_array($this->result))
        return $this->response->json([ ...$this->result ])->print();
    }
  }