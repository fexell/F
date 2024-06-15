<?php

  namespace F\Http\Request;

  use F\Http\Request;
  use F\Http\Response\Json;
  use F\Http\Response\Error;

  class Parameters extends Request {
    private string | null $param = null;

    public function __construct(string | null $param = null) {
      $this->param = $param;
    }
  }