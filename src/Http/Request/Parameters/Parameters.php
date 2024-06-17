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

    /**
     * @param string|null $param The parameter to use
     */
    public function __construct(string | array | null $param = null) {
      if(!is_string($param) && !is_array($param) && !is_null($param))
        exit('The params parameter needs to be either a string, an array, or left empty.');

      $this->response = new Response();
      $this->param = $param;
    }

    public function get() {
      $result = [];

      if(is_string($this->param))
        if(empty($_GET[ $this->param ]))
          exit('Could not find a value for key "' . $this->param . '."');

        else
          return $_GET[ $this->param ];

      else if(is_array($this->param)) {
        foreach($this->param as $param)
          if(empty($_GET[ $param ]))
            return $this->response->error('Could not find a value for parameter "' . $param . '."')->print();

          else
            $result[ $param ] = $_GET[ $param ];

        return $this->response->json($result);
      }
    }

    public function toJson() {
      if(is_string($this->get()))
        return $this->response->json([ $this->param => $this->get() ]);

      else if(is_array($this->get()))
        return $this->get();
    }

    public function all() {
      if(is_null($this->param))
        return $this->response->json($_GET);
    }
  }