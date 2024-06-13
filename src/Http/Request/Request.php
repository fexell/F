<?php

  namespace F\Http;

  use F\Http\Parameters;

  class Request {
    public static array $params = [];

    public function params(string $param) {
      return new Parameters($param);
    }
  }