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
  }