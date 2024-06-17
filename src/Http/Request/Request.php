<?php

  namespace F\Http;

  use F\Http\Request\Parameters;

  class Request {

    /**
     * @var array $params The array to hold the parameters, for the params method below
     */
    public static array $params = [];

    /**
     * The params request method
     * @param string|array|null $param The parameter to reference
     * @return Parameters
     */
    public function params(string | array | null $param = null) {
      return new Parameters($param);
    }
  }